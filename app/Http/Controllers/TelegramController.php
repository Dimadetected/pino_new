<?php

namespace App\Http\Controllers;


use App\Http\Services\TgService;
use App\Http\TelegramTemplates\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function __construct()
    {
        $this->telegram = new TgService('1693125992:AAFku3IyNSELpLporEaWmuehK8qNok8p0z8');
    }

    public function getMe()
    {
        dd($this->telegram->getMe());
    }

    public function setWebHook()
    {
        $this->telegram->setWebHook();
    }


    public function handle(Request $request)
    {
        logger($request);
        if (isset($request['message']['from']['id'])) {
            (new Auth())->index($request);
        }
        return 200;
    }

}
