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
use App\Models\Event;
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
        $pregunta_secreta = false;
        $from = $request->input('From');
        $body = $request->input('Body');

        // validar la sesión
        $session = Session::where('from', $from)->first();
        $sessionController = new SessionController();
        if (is_null($session)) {
            $session = $sessionController->store($from);
            $this->saveEvent('Iniciar sesion', '');
        } else {
            $sessionController->update($session);
            $this->saveEvent('Actualizar sesion', '');
        }

        $step = intval($session->step);
        $step = $step == 0 ? 1 : $step;

        $message = Message::where('id', $step)->first();
        $data = $session->data;
        $json = json_decode($data, true);

        $menu = $json['menu'];

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
            case 1:
                $this->saveEvent('Bienvenida', '');
                break;
            case 2:
                $this->saveEvent('Ingresa nombres', '');
                $json['nombres'] = $body;
                break;
            case 3:
                $this->saveEvent('Ingresa dni', '');
                $json['dni'] = $body;
                break;
            case 4:
                $this->saveEvent('Ingresa cip', '');
                $json['cip'] = $body;
                break;
            case 5:
                $this->saveEvent('Ingresa institucion', '');
                $json['institucion'] = $body;
                $validar = true; // validar datos
                break;
            case 6:
                $this->saveEvent('Responde pregunta secreta', '');
                $pregunta_secreta = true; // pregunta secreta
                break;
            case 7:
                $this->saveEvent('Muestra menu', '');
                $json['opcion_menu'] = $body;
                $message = $this->execMenu($body, $session);
                $this->sendWhatsAppMessage($message, $from);
                return;
            case 100:
                $this->saveEvent('Regresar a menu', '');
                // regresar a menu
                $json['menu'] = 0;
                $message_temp = $this->showMenu();
                break;
            case 999:
                $this->saveEvent('Salir', '');
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
            $message = "Danos unos minutos, estamos validando tus datos. \n\n";
            $ok = $this->sendWhatsAppMessage($message, $from);
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
                $this->saveEvent('Error al validar los datos', '');
                // error
                $message = Message::where('id', 9)->first();
                $message = $message->description;
                $session->data = json_encode($json);
                $session->save();
            } else {
                $this->saveEvent('Validacion exitosa', '');
                // ok - almacenar id de pensionista
                $json['id_pensioner'] = $pensioner->id;
                $json['first_name'] = $pensioner->first_name;
                $session->data = json_encode($json);
                $session->save();
                $message = Message::where('id', 5)->first();
                $message = $message->description;
                $message = Str::of($message)->replace('__NOMBRE__', $pensioner->first_name);
                // mostrar mensaje de pregunta de seguridad
                $message .= $this->showPreguntaSecreta();
            }
        } else {
            if ($pregunta_secreta) {
                $message = Message::where('id', 5)->first();
                $message = $message->description;
                $message = Str::of($message)->replace('__NOMBRE__', $json['first_name']);
                $message .= "\n\n";
                $message .= $this->showMenu();
            } else
                $message = $message->description ?? $message_temp;
        }

        $this->sendWhatsAppMessage($message, $from);

        return 'listenToReplies';
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

        $menu = 'Opcion #' . $option;
        switch($option) {
            case 1:
                $menu = 'Opcion 1 Monto de pago del mes en curso';
                $json['menu'] = 1;
                $payment = Payment::where('id_pensioner', $id_pensioner)->first();
                $message = "Estimado/a $first_name, este mes le corresponde recibir S/$payment->mount \n\n";
                break;
            case 2:
                $menu = 'Opcion 2 Acerca de su boleta de pago';
                $json['menu'] = 2;
                $url = asset('pdf/boleta.pdf');
                $message = "Estimado/a $first_name, adjunto boleta de pago del mes en curso: $url  \n\n";
                break;
            case 3:
                $menu = 'Opcion 3 Fecha de depósito';
                $json['menu'] = 3;
                $payment = Payment::where('id_pensioner', $id_pensioner)->first();
                $date = $payment->payment_day;
                $payment_day = date("d/m/Y", strtotime($date));
                $message = "Estimado/a $first_name, su depósito este mes será realizado el $payment_day  \n\n";
                break;
            case 4:
                $menu = 'Opcion 4 Estado de trámite/reclamo';
                $json['menu'] = 44;
                $message = "Estimado/a $first_name, para poder atender su consulta por favor ingrese el código del trámite que desea consultar.  \n\n";
                // no mostrar opciones de salida
                $flag = false;
                break;
            case 44:
                $menu = 'Opcion 4 Estado de trámite/reclamo - Ingresa codigo';
                $json['menu'] = 44;
                $claim_code = session('claim_code', null);
                $claim = Claim::where('code', $claim_code)->first();
                if ($claim) {
                    $date = $claim->end_date;
                    $end_date = date("d/m/Y", strtotime($date));
                    $message = "Estimado/a $first_name, su reclamo 2023-$claim_code fue admitido por la OPREFA, actualmente se encuentra en la fase de: REGISTRADO, a cargo de Miguel Peláez (miguel.pelaez@oprefa.gob.pe anexo 456) y la fecha estimada de término del mismo es el $end_date  \n\n";
                } else {
                    $message = "Estimado/a $first_name, no hemos encontrado registro con el código ingresado.  \n\n";
                }

                break;
            case 5:
                $menu = 'Opcion 5 Comunicarse con el Analista del área de pensiones';
                $json['menu'] = 5;
                $message = "Estimado/a $first_name, el analista del área de pensiones está DISPONIBLE, por lo que él continuará con la atención, fue un placer atenderlo, ¡Que tenga un buen día!  \n\n";
                $message .= "Hola soy Pepita, estaré encargada de su atención.  \n\n";
                $flag = false;
                break;
            default:
                $menu = 'Opcion incorrecta';
                $json['menu'] = 1;
                $message = "Estimado/a $first_name, la opción ingresada es incorrecta vuelva a intentar.  \n\n";
                break;
        }

        $this->saveEvent('Menu', $menu);

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

    public function showPreguntaSecreta()
    {
        $message = "\n";
        $message .= "*Pregunta Secreta.* Por su seguridad conteste la siguiente pregunta: *¿Cuál es el nombre de su padre?*  \n\n";
        $message .= '*Por favor, escriba su respuesta a continuación.*';
        return $message;
    }

    private function saveEvent($step = '', $menu = '')
    {
        $now = now();
        $event = new Event();
        $event->date = $now;
        $event->step = $step;
        $event->menu = $menu;
        $event->save();
        return true;
    }
}
