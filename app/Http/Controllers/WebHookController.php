<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\Api\ApiPeruController;
use Twilio\Rest\Client;
use App\Models\Session;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Claim;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{

    public function __construct()
    {
        //








        //
    }

    public function listenToReplies(Request $request)
    {
        $validar = false;
        $message_temp = '';
        $from = $request->input('From');
        $body = $request->input('Body');

        // validar la sesión
        $session = Session::where('from', $from)->first();
        $sessionController = new SessionController();
        if (is_null($session)) {
            $session = $sessionController->store($from);
        } else {
            $sessionController->update($session);
        }

        $step = $session->step;
        $message = Message::where('id', $step)->first();
        $data = $session->data;
        $json = json_decode($data, true);

        $menu = $json['menu'];

        Log::info("***************************");
        Log::info("objeto json");
        Log::info($json);
        Log::info("menu: $menu");
        Log::info("body: $body");

        if ($menu >= 1 && $body == 2 || $body == 6) {
            $step = 999;
        } elseif ($menu >= 1 && $body == 1) {
            $step = 100;
        } elseif ($menu == 44) {
            // dentro de menu - consulta de tramite
            session(['claim_code' => $body]);
            $message_temp = $this->execMenu('44', $session);
            $step = 44;
        }

        switch($step) {
            case 2:
                $json['nombres'] = $body;
                break;
            case 3:
                $json['dni'] = $body;
                break;
            case 4:
                $json['cip'] = $body;
                break;
            case 5:
                $json['institucion'] = $body;
                $validar = true;
                break;
            case 6:
                $json['opcion_menu'] = $body;
                $message = $this->execMenu($body, $session);
                $this->sendWhatsAppMessage($message, $from);
                return;
            case 100:
                // mostrar menu
                $json['menu'] = 0;
                $message_temp = $this->showMenu();
                break;
            case 999:
                // salir
                $first_name = $json['first_name'];
                $message = "Fue un placer atenderlo $first_name, recuerde que, si tiene alguna otra consulta, puede comunicarse conmigo en cualquier momento del día, ¡todos los días!";
                $sessionController->close($session);
                $this->sendWhatsAppMessage($message, $from);
                return;
        }

        if ($step > 1) {
            $session->data = json_encode($json);
            $session->save();
        }

        if ($validar) {
            // validar RENIEC por DNI y actualizar fullname
            $apiReniec = new ApiPeruController();
            $response = $apiReniec->getDni($body);
            if ($response && $response->success) {
                DB::table('pensioners')
                    ->where('dni', 1)
                    ->update(['fullname' => $response->data->nombre_completo]);
            }

            // query validate
            $pensioner = DB::table('pensioners')
                ->select('id', 'first_name')
                ->where('dni', $json['dni'])
                ->where('cip', $json['cip'])
                ->where('institution', $json['institucion'])
                ->whereFullText('fullname', $json['nombres'])
                ->first();
            if (!$pensioner) {
                // error
                $message = Message::where('id', 9)->first();
                $message = $message->description;
                $session->data = json_encode($json);
                $session->save();
            } else {
                // ok - almacenar id de pensionista
                $json['id_pensioner'] = $pensioner->id;
                $json['first_name'] = $pensioner->first_name;
                $session->data = json_encode($json);
                $session->save();
                $message = $message->description;
                $message = Str::of($message)->replace('__NOMBRE__', $pensioner->first_name);
                $message .= "\n\n";
                $message .= $this->showMenu();
            }
        } else {
            $message = $message->description ?? $message_temp;
        }

        $this->sendWhatsAppMessage($message, $from);

        return;
    }

    /**
     * Sends a WhatsApp message  to user using
     * @param string $message Body of sms
     * @param string $recipient Number of recipient
     */
    public function sendWhatsAppMessage(string $message, string $recipient)
    {
        $twilio_whatsapp_number = config('services.twilio.whatsapp_from');
        $account_sid = config('services.twilio.sid');
        $auth_token = config('services.twilio.token');
        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($recipient, array('from' => $twilio_whatsapp_number, 'body' => $message));
    }

    /**
     * Function for menu options
     * @param string $option the option for menu
     * @param Session data session
     */
    public function execMenu(string $option, Session &$session)
    {
        $flag = true;
        $message = '';
        $option = (int)$option;
        $data = $session->data;
        $json = json_decode($data, true);
        $id_pensioner = $json['id_pensioner'];
        $first_name = $json['first_name'];

        switch($option) {
            case 1:
                $json['menu'] = 1;
                $payment = Payment::where('id_pensioner', $id_pensioner)->first();
                $message = "Estimado/a $first_name, este mes le corresponde recibir S/$payment->mount \n\n";
                break;
            case 2:
                $json['menu'] = 2;
                $url = asset('pdf/boleta.pdf');
                $message = "Estimado/a $first_name, adjunto boleta de pago del mes en curso: $url  \n\n";
                break;
            case 3:
                $json['menu'] = 3;
                $payment = Payment::where('id_pensioner', $id_pensioner)->first();
                $date = $payment->payment_day;
                $payment_day = date("d/m/Y", strtotime($date));
                $message = "Estimado/a $first_name, su depósito este mes será realizado el $payment_day  \n\n";
                break;
            case 4:
                $json['menu'] = 44;
                $message = "Estimado/a $first_name, para poder atender su consulta por favor ingrese el código del trámite que desea consultar.  \n\n";
                // no mostrar opciones de salida
                $flag = false;
                break;
            case 44:
                $json['menu'] = 44;
                $claim_code = session('claim_code', null);
                $claim = Claim::where('code', $claim_code)->first();
                $date = $claim->end_date;
                $end_date = date("d/m/Y", strtotime($date));
                $message = "Estimado/a $first_name, su reclamo 2023-$claim_code fue admitido por la OPREFA, actualmente se encuentra en la fase de: REGISTRADO, a cargo de Miguel Peláez (miguel.pelaez@oprefa.gob.pe anexo 456) y la fecha estimada de término del mismo es el $end_date  \n\n";
                break;
            case 5:
                $json['menu'] = 5;
                $message = "Estimado/a $first_name, el analista del área de pensiones está DISPONIBLE, por lo que él continuará con la atención, fue un placer atenderlo, ¡Que tenga un buen día!  \n\n";
                break;
            default:
                $json['menu'] = 1;
                $message = "Estimado/a $first_name, la opción ingresada es incorrecta vuelva a intentar.  \n\n";
                break;
        }

        if ($flag) {
            $message .= "¿Tiene alguna otra consulta? \n";
            $message .= "1. Si \n";
            $message .= "2. No \n";
        }

        $session->data = json_encode($json);
        $session->save();
        return $message;
    }

    public function showMenu()
    {
        $message = "*¿Qué operación desea realizar?*  \n\n";
        $message .= "*1*. Monto de pago del mes en curso. \n";
        $message .= "*2*. Acerca de su boleta de pago. \n";
        $message .= "*3*. Fecha de depósito. \n";
        $message .= "*4*. Estado de trámite/reclamo. \n";
        $message .= "*5*. Comunicarse con el Analista del área de pensiones. \n";
        $message .= "*6*. Salir. \n\n";
        $message .= '*Por favor, elija una opción.*';
        return $message;
    }

}
