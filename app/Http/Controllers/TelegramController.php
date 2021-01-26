<?php

namespace App\Http\Controllers;


use App\Http\Services\TgService;
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

        return 200;
    }

}
