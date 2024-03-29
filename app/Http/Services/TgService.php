<?php

namespace App\Http\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class TgService
{

    private $token, $url;

    public function __construct($token)
    {
        $this->token = $token;
        $this->url = 'https://api.telegram.org/bot' . $this->token;
    }

    /**
     * Получение информации о боте
     * @return string
     */
    public function getMe()
    {
        $response = Http::get($this->url . '/getMe');
        return $response->body();
    }


    /**
     * Установление вебхука
     * @return \Illuminate\Http\Client\Response|void
     */
    public function setWebHook()
    {
        $url = 'https://xn--h1akdb.xn--p1acf/' . $this->token . '/webhook';
        $response = Http::get($this->url . '/setWebhook', [
            'url' => $url
        ]);
        dd(dd($response->body()));
        return $response == TRUE ? $response : dd($response->body());
    }


    /**
     * Отправление сообщений
     * @param $arr
     */
    public function sendMessage($arr)
    {
        $response = Http::post($this->url . '/sendMessage', $arr);
        return $response->body();
    }

    public function sendChatAction($arr)
    {
        $response = Http::post($this->url . '/sendChatAction', $arr);
        return json_decode($response->body(),true);
    }
}
