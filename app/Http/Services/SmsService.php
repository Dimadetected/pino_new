<?php


namespace App\Http\Services;


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
            "scheduleTime" => now()->addSeconds(10),
            "login" => $this->login,
            "password" => $this->password,
            "messages" => [
                "phone" => $phone,
                "sender" => "Пино",
                "text" => $text
            ]
        ];
        dd($data);
    }
}
