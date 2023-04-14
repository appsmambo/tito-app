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
                // ok
                $message = $message->description;
                $message = Str::of($message)->replace('__NOMBRE__', $pensioner->first_name);
                $message .= "\n\n";
                $message .= "*¿Qué operación desea realizar?*  \n\n";
                $message .= "*1* - Monto de pago del mes en curso. \n";
                $message .= "*2* - Acerca de su boleta de pago. \n";
                $message .= "*3* - Fecha de depósito. \n";
                $message .= "*4* - Estado de trámite/reclamo. \n";
                $message .= "*5* - Comunicarse con el Analista del área de pensiones. \n\n";
                $message .= '*Por favor, elija una opción.*';
            }
        } else {
            $message = $message->description;
        }

        /*
        if (is_null($message)) {
            $message = '*Usted ingresó:* ' . $json['nombres'] . ' | ' . $json['dni'] . ' | ' . $json['cip'] . ' | ' . $json['institucion'] . "\n\n";
            $message .= "*¿Qué operación desea realizar?*  \n\n";
            $message .= "*1* - Monto de pago del mes en curso. \n";
	        $message .= "*2* - Acerca de su boleta de pago. \n";
	        $message .= "*3* - Fecha de depósito. \n";
	        $message .= "*4* - Estado de trámite/reclamo. \n";
	        $message .= "*5* - Comunicarse con el Analista del área de pensiones. \n\n";
	        $message .= '*Por favor, elija una opción.*';
        } else {
            $message = $message->description;
        }
        */
        $this->sendWhatsAppMessage($message, $from);



        /*
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', "https://api.github.com/users/$body");
            $githubResponse = json_decode($response->getBody());
            if ($response->getStatusCode() == 200) {
                $message = "*Name:* $githubResponse->name\n";
                $message .= "*Bio:* $githubResponse->bio\n";
                $message .= "*Lives in:* $githubResponse->location\n";
                $message .= "*Number of Repos:* $githubResponse->public_repos\n";
                $message .= "*Followers:* $githubResponse->followers devs\n";
                $message .= "*Following:* $githubResponse->following devs\n";
                $message .= "*URL:* $githubResponse->html_url\n";
                $this->sendWhatsAppMessage($message, $from);
            } else {
                $this->sendWhatsAppMessage($githubResponse->message, $from);
            }
        } catch (RequestException $th) {
            $response = json_decode($th->getResponse()->getBody());
            $this->sendWhatsAppMessage($response->message, $from);
        }
        */

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
}
