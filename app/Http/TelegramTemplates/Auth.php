<?php


namespace App\Http\TelegramTemplates;

use App\Http\Services\TgService;
use App\Models\User;
use Illuminate\Http\Request;


class Auth
{

    public function __construct()
    {
        $this->telegram = new TgService('1693125992:AAFku3IyNSELpLporEaWmuehK8qNok8p0z8');
    }

    public function index(Request $request)
    {
        $tg_id = $request['message']['from']['id'];
        $text = $request['message']['text'];
        $user = User::query()->where('tg_id', $tg_id)->first();
        if ($user)
            $text = 'Вы уже авторизированы в боте.';
        else {
            $user_check_code = User::query()->where('remember_token', $text)->first();
            if ($user_check_code){
                $user_check_code->update(['tg_id' => $tg_id]);
                $text = $user_check_code->name . ', ' . ' вы были успешно авторизованы.';
            }
            else
                $text = 'Для авторизации введите ваш TG_code из личного кабинета.';
        }

        $this->telegram->sendMessage([
            'chat_id' => $user->tg_id,
            'text' => $text
        ]);
    }
}
