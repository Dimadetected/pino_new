<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillResource;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class MessageController extends Controller
{

    public function index()
    {
    }

    public function store(Request $request)
    {
        if ($request->type == 'bill') {
            $bill = Bill::query()->find($request->external_id);
            $bill->alerts_count_inc();

            $buttons = [];
            $users_id = BillAction::query()
                ->where("bill_id", $bill->id)
                ->groupBy("user_id")
                ->pluck("user_id")
                ->toArray();
            $users = User::query()->find($users_id);
            foreach ($users as $user) {
                $buttons[] = [['text' => 'Счет', 'url' => route('bill.view', $bill->id)]];
                echo $user->id . "\n";
                echo $user->tg_notice . "\n";
                if (isset($user->tg_id) and !is_null($user->tg_notice)) {
                    echo $user->tg_id . "\n";
                    logger($this->telegram->sendMessage([
                        'chat_id' => $user->tg_id,
                        'text' => "В счете №" . $bill->id . " был оставлен комментарий: \n" . $request->text,
                        'reply_markup' => json_encode(['inline_keyboard' =>
                            $buttons,
                        ]),
                    ]));

                }
            }
        }


        return response()->json(Message::query()->create([
            'type' => $request->type,
            'external_id' => $request->external_id,
            'user_id' => $request->user_id,
            'text' => $request->text,
        ]));
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
