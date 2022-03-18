<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillResource;
use App\Http\Services\TgService;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

class MessageController extends Controller
{
    private $telegram;

    public function index()
    {
    }

    public function fileUpload($id)
    {
        return $id;
    }

    public function store(Request $request)
    {
        $this->telegram = new TgService('1693125992:AAFku3IyNSELpLporEaWmuehK8qNok8p0z8');
        $sender = User::query()->find($request->user_id);
        if ($request->type == 'bill') {
            $bill = Bill::query()->find($request->external_id);
            $bill->alerts_count_inc();

            $buttons = [];
            $users_id = BillAction::query()
                ->where("bill_id", $bill->id)
                ->groupBy("user_id")
                ->pluck("user_id")
                ->toArray();
            $users_id = array_merge($users_id, $bill->messages()->groupBy("user_id")->pluck("user_id")->toArray());
            $users = User::query()->find($users_id);
            foreach ($users as $user) {
                $buttons[] = [['text' => 'Счет', 'url' => route('bill.view', $bill->id)]];
                if (isset($user->tg_id) and !is_null($user->tg_notice)) {
                    logger($this->telegram->sendMessage([
                        'chat_id' => $user->tg_id,
                        'text' => "В счете №" . $bill->id . ".\n" . $sender->name . " оставлил комментарий: \n" . $request->text,
                        'reply_markup' => json_encode(['inline_keyboard' =>
                            $buttons,
                        ]),
                    ]));
                }
                if (isset($user->phone) and !is_null($user->sms_notice)) {
//                    $this->sms->send($user->phone, "В счете №" . $bill->id .".\n". $sender->name . " оставлил комментарий: \n");
                }
            }
        }


        $message = Message::query()->create([
            'type' => $request->type,
            'external_id' => $request->external_id,
            'user_id' => $request->user_id,
            'text' => $request->text,
        ]);

        $files = [];
        if (isset($request->files)) {
            foreach ($request->files as $moreFiles) {
                foreach ($moreFiles as $file) {

                    if (!is_dir(public_path('files')))
                        mkdir(public_path('files'), 0777, TRUE);
                    if (!is_dir(public_path('files/messages')))
                        mkdir(public_path('files/messages'), 0777, TRUE);

                    $extension = $file->getClientOriginalExtension();
                    $path = 'files/messages/messageID' . $message->id . '.' . $extension;
                    File::put(public_path($path), file_get_contents($file));
                    $files[] = $path;

                }
            }
        }

        $file = \App\Models\File::query()->create([
            'src' => $files,
        ]);

        return redirect()->back();
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
