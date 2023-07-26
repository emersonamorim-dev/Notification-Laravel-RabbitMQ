<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use GuzzleHttp\Client;
use App\Mail\EmailNotification;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function consumeQueue()
    {
        $queueName = 'notifications';

        $connection = new AMQPStreamConnection('localhost', 5672, 'user', 'pass');
        $channel = $connection->channel();

        $channel->queue_declare($queueName, false, true, false, false);

        $callback = function (AMQPMessage $message) {
            try {
                $notificationData = json_decode($message->getBody(), true);

                if ($notificationData['type'] === 'email') {
                    $this->sendEmailNotification($notificationData['data']);
                } elseif ($notificationData['type'] === 'push') {
                    $this->sendPushNotification($notificationData['data']);
                }

                $message->ack(); // Confirmação de recebimento da mensagem (ACK)
            } catch (\Exception $e) {

                $message->reject(); // Rejeitar a mensagem (NACK) em caso de erro para que ela não seja removida da fila
            }
        };

        $channel->basic_consume($queueName, '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();

        return response()->json(['message' => 'Notificações processadas com sucesso.']);
    }

    private function sendEmailNotification(array $data)
    {
        $to = $data['email'];
        $subject = 'Notificação Importante';
        $message = 'Olá, você recebeu uma notificação importante!';
    
        // Use a função de e-mail do Laravel para enviar a notificação
        try {
            Mail::to($to)->send(new EmailNotification($subject, $message));
        } catch (\Exception $e) {

            throw $e;
        }
    }
    
    
         private function sendPushNotification(array $data)
     {
         $deviceToken = $data['device_token']; // Token do dispositivo para onde enviar a notificação
         $title = 'Notificação Importante'; 
         $message = 'Olá, você recebeu uma notificação importante!'; 
     
         // Dados da notificação a serem enviados para o dispositivo
         $notification = [
             'title' => $title,
             'body' => $message,
         ];
     
         // Dados adicionais a serem enviados para o aplicativo, caso ele precise processá-los
         $dataPayload = [
             'type' => 'important_notification',
         ];
     
         // Montar a requisição com os dados da notificação e payload de dados
         $requestData = [
             'to' => $deviceToken,
             'notification' => $notification,
             'data' => $dataPayload,
         ];
     
         // Configurar o cliente Guzzle para fazer a solicitação HTTP para o FCM
         $client = new Client();
         $response = $client->post('https://fcm.googleapis.com/fcm/send', [
             'headers' => [
                 'Authorization' => 'key=SEU_API_KEY_FCM', 
                 'Content-Type' => 'application/json',
             ],
             'json' => $requestData,
         ]);
     
         $responseBody = json_decode($response->getBody(), true);
     
         if ($responseBody['success'] == 1) {
     
         } else {
     
             throw new \Exception('Erro ao enviar a notificação push.');
         }
     }
}