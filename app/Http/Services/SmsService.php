<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\Http;

class SmsService
{
    private $login, $password;

    public function __construct()
    {
        $this->login = env("SMS_LOGIN");
        $this->password = env("SMS_PASSWORD");
    }

    public function send($phone, $text)
    {
        $data = [
            "login" => $this->login,
            "password" => $this->password,
            "sender" => "Pino",
            "messages" => [
                "phone" => $phone,
                "text" => $text,
                "sender" => "Pino",
            ]
        ];

        $post = Http::post("http://api.prostor-sms.ru/messages/v2/send.json", $data);

        logger($data);
        logger($post->body());
    }
}
