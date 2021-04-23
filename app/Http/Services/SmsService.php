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
            "scheduleTime" => now()->addSeconds(10)->toDateTime(),
            "login" => $this->login,
            "password" => $this->password,
            "messages" => [
                "phone" => $phone,
                "sender" => "Пино",
                "text" => $text
            ]
        ];

        $post = Http::post("http://api.prostor-sms.ru/messages/v2/send.json",$data);
        dd($post->body());
    }
}
