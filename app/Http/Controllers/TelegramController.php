<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function __construct()
    {
    }

    public function getMe()
    {
        $this->telegram = new TelegramService('1693125992:AAFku3IyNSELpLporEaWmuehK8qNok8p0z8');
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
