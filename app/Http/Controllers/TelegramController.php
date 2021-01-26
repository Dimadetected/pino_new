<?php

namespace App\Http\Controllers;

use App\Jobs\TimetableCreate;
use App\Jobs\TimetableNoticeEnd;
use App\Jobs\TimetableNoticeStart;
use App\Models\Client;
use App\Models\RedBtnQuestion;
use App\Models\RedBtnUser;
use App\Models\TelegramMessage;
use App\Models\Timetable;
use App\Services\TelegramService;
use App\Telegram;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use function App\Helpers\phoneToBase;

class TelegramController extends Controller
{
    public function __construct()
    {
        $this->telegram = new TelegramService('1693125992:AAFku3IyNSELpLporEaWmuehK8qNok8p0z8');
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
