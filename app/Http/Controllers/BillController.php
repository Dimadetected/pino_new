<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillFormRequest;
use App\Http\Services\SmsService;
use App\Http\Services\TgService;
use App\Mail\OrderShipped;
use App\Models\Bill;
use App\Models\BillAction;
use App\Models\BillStatus;
use App\Models\BillType;
use App\Models\Chain;
use App\Models\Client;
use App\Models\Message;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Exception;
use Gufy\PdfToHtml\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use setasign\Fpdi\Fpdi;
use ZendPdf\Color\Html;
use ZendPdf\Font;
use ZendPdf\Image;
use ZendPdf\PdfDocument;

class BillController extends Controller
{
    private $views = [
        'index' => 'bills.admin.index',
        'view' => 'bills.admin.view',
        'form' => 'bills.admin.form',
    ];
    private $routes = [
        'index' => 'bill.index',
        'view' => 'bill.view',
        'form' => 'bill.form',
        'store' => 'bill.store',
        'worked' => 'application.worked',
    ];

    public function check()
    {
        return 1;
    }

    public function __construct()
    {
        $this->telegram = new TgService(env("TGBOT"));
        $this->sms = new SmsService();
    }

    public function index()
    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);

        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);

        $user = auth()->user();
        if (is_null($user->remember_token))
            $user->update(['remember_token' => rand(111111111, 99999999999)]);


        $org_ids = $user->org_ids;
        $user_id = $user->id;
        $bills = Bill::query()
            ->orderBy('status')
            ->where('user_role_id', $user->user_role_id)
            ->orWhere('user_id', $user->id)
            ->with(['user', 'bill_type', 'bill_status', 'bill_actions'])
            ->with('bill_alerts', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->with('chain', function ($query) {
                $query->where('type', 1);
            })
            ->orderBy('created_at', 'desc');
        $bills = $bills->whereBetween('created_at', [$date_start, $date_end]);
        if ($billNumber != 0 and $billNumber != "") {
            $bills = $bills->where("id", "=", $billNumber);
        }
        if ($contragentID != 0) {
            $bills = $bills->where("client_id", "=", $contragentID);
        }
        if ($billCreatorID != 0) {
            $bills = $bills->where("user_id", "=", $billCreatorID);
        }
        $bills = $bills->where("status",1)->get();
        $header = 'Счета';
        $bill_type = 'счета';
        if (auth()->user()->read_only == true){
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '</div>';
        }else{
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
                '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
                '</div>';
        }

        return view($this->views['index'],
            compact('bill_type', 'org_ids', 'date_start', 'date_end', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function view(Bill $bill)
    {
        $user = auth()->user();

        if ($bill->chain->type == 1)
            $header = 'Информарция о счете';
        else
            $header = 'Информарция о заявке';

        $print_file = $bill->file->src[0] ?? "";
        foreach ($bill->bill_actions as $action)
            $action->new_date = Carbon::parse($action->created_at)->format('d.m.Y H:i');
        foreach ($bill->messages as $message) {
            $message->new_date = Carbon::parse($message->created_at)->format('d.m.Y H:i');
            $file = \App\Models\File::query()->where('src', 'LIKE', '%messageID' . $message->id . '%')->first();
            $message->images = [];
            if (isset($file->src) and count($file->src) > 0)
                $message->images = $file->src;
        }
        if (isset($bill->file->src[0])) {
            $src = explode('.', $bill->file->src[0]);
            if (array_pop($src) == 'pdf') {
                $bill_user_status = $bill->mainUserAccept();
                if ($bill_user_status and isset($bill_user_status)) {
                    $bill_user_status = $bill_user_status->user;
                    $img = public_path("white.png"); // Ссылка на файл
                    $font = public_path("9605.ttf"); // Ссылка на шрифт
                    $font_size = 24; // Размер шрифта
                    $degree = 0; // Угол поворота текста в градусах
                    $text = 'Утверждено ' . $bill_user_status->name; // Ваш текст
                    $y = 40; // Смещение сверху (координата y)
                    $x = 40; // Смещение слева (координата x)
                    $pic = imagecreatefrompng($img); // Функция создания изображения
                    $color = imagecolorallocate($pic, 6, 170, 252); // Функция выделения цвета для текста

                    imagettftext($pic, $font_size, $degree, $x, $y, $color, $font, $text); // Функция нанесения текста
                    $text = Carbon::parse($bill->mainUserAccept()->created_at)->format('d.m.Y H:i:s');
                    $y = 80; // Смещение сверху (координата y)
                    $x = 400; // Смещение слева (координата x)
                    imagettftext($pic, $font_size, $degree, $x, $y, $color, $font, $text); // Функция нанесения текста
                    imagepng($pic, 'accept' . ".png"); // Сохранение рисунка
                    imagedestroy($pic); // Освобождение памяти и закрытие рисунка

                    $pdf = new Fpdi();
                    $pages_count = $pdf->setSourceFile($bill->file->src[0]);
                    for ($i = 1; $i <= $pages_count; $i++) {
                        $pdf->AddPage();

                        $tplIdx = $pdf->importPage($i);

                        $pdf->useTemplate($tplIdx, 0, 0);
                        if ($i == $pages_count) {
                            $pdf->Image(public_path('accept.png'), 20, 250, 100, 20);
                            $pdf->Output(public_path('files/' . $bill->id . '.pdf'), 'F');
                            $print_file = 'files/' . $bill->id . '.pdf';
                        }
                    }

                }
            }
        }
        $routes = $this->routes;
        $alert = $bill->bill_alerts()->where('user_id', $user->id)->first();
        if (isset($alert->count))
            $alert->update(['count' => 0]);
        return view($this->views['view'], compact('bill', 'user','routes', 'header', 'print_file'));
    }

    public function form(Bill $bill)
    {
        $user = auth()->user();
        if (!isset($bill->id)) {
            $bill = new Bill();
            if (isset($_COOKIE['chain_id']))
                $bill->chain_id = $_COOKIE['chain_id'];
        }

        $clients = Client::query()->orderBy("name", "asc")->get();
        $chains = Chain::query()->whereIn('organisation_id', $user->org_ids)->get();
        $header = 'Форма счета';
        return view($this->views['form'], compact('clients', 'bill', 'header', 'chains'))->with('routes', $this->routes);
    }

    public function consult()
    {
        $bill = Bill::query()->find(\request('bill'));
        $billArr = [];
        $type = \request('type');
        $user = User::query()->find(\request('user_id'));
//        if (in_array(auth()->user()->user_role_id, $bill->chain->value) and !in_array(auth()->user()->user_role_id, [6, 7, 4])) {
//            $bill->update(['steps' => array_search(auth()->user()->user_role_id, $bill->chain->value), 'user_role_id' => auth()->user()->user_role_id]);
//        }

        $bill_status = $bill->bill_statuses();
        if ($type == 'accept') {
            $status = 1;
            $bill_status = $bill_status->where('status', 'good')->first();
            $bill_status_id = $bill_status->id;
            $text = $bill_status->name;
            $billArr['steps'] = $bill->steps;
            if ($billArr['steps'] != 0 and isset($bill->chain->value[$billArr['steps']]) and $user->user_role_id != $bill->chain->value[$billArr['steps']]) {
                return response()->json($bill);
            }
            $billArr['steps'] = $bill->steps + 1;

            if (isset($bill->chain->value[$billArr['steps']]))
                $billArr['user_role_id'] = $bill->chain->value[$billArr['steps']];
            else
                $billArr['user_role_id'] = NULL;
            $return = 'good';
        } else {
            $bill_status = $bill_status->where('status', 'bad')->first();
            $status = 2;
            $bill_status_id = $bill_status->id;
            $text = $bill_status->name;
            $return = 'bad';
        }


        $billArr['status'] = $status;
        $billArr['bill_status_id'] = $bill_status_id;

        if ($return == 'good' and isset($billArr['bill_type_id']))
            $billArr['bill_type_id'] = BillType::query()->where('user_role_id', $billArr['user_role_id'])->first()->id;

        $bill->bill_log()->create([
            'info' => [
                'status' => $bill->status,
                'bill_type_id' => $bill->bill_type_id,
                'user_role_id' => $bill->user_role_id,
                'bill_status_id' => $bill->bill_status_id,
                'steps' => $bill->steps,
            ],
        ]);
        $bill->update($billArr);

        $action = BillAction::query()->create([
            'bill_id' => $bill->id,
            'user_id' => auth()->user()->id,
            'status' => $status,
            'text' => $text,
        ]);

        if (\request('text'))
            Message::query()->create([
                'type' => 'bill_action',
                'external_id' => $action->id,
                'user_id' => auth()->user()->id,
                'text' => \request('text'),
            ]);


        $buttons = [];
        $buttons[] = [['text' => 'Счет', 'url' => route('bill.view', $bill->id)]];
        if (isset($bill->user->tg_id) and !is_null($bill->user->tg_notice)) {
            logger($this->telegram->sendMessage([
                'chat_id' => $bill->user->tg_id,
                'text' => $text,
                'reply_markup' => json_encode(['inline_keyboard' =>
                    $buttons,
                ]),
            ]));
        }
        if (isset($bill->user->phone) and !is_null($bill->user->sms_notice)) {
            $this->sms->send($bill->user->phone, $text . "\n" . "Счет: " . $bill->id);
        }

        $bill->alerts_count_inc();

        if (!is_null($bill->user->email_notice))
            try {
                Mail::to($bill->user->email)->send(new \App\Mail\Bill($bill, $text));
            } catch (\Throwable $e) {
                logger($e->getMessage());
            }

        if ($bill->status == 1) {
            $organisation_id = $bill->chain->organisation_id;
            $users = User::query()
                ->where('user_role_id', $bill->user_role_id)
                ->whereHas('organisations', function ($query) use ($organisation_id) {
                    $query->where('organisation_id', $organisation_id);
                })->get();

            foreach ($users as $user) {

                if (isset($user->phone) and !is_null($user->sms_notice)) {
                    $this->sms->send($user->phone, $text . "\n" . "Счет: " . $bill->id);
                }

                if (!is_null($user->tg_notice))
                    logger($this->telegram->sendMessage([
                        'chat_id' => $user->tg_id,
                        'text' => 'Поступил счет ' . $bill->id . ' на утверждение от ' . $bill->user->name . '.',
                        'reply_markup' => json_encode(['inline_keyboard' =>
                            $buttons,
                        ]),
                    ]));
                if (!is_null($user->email_notice))
                    try {
                        Mail::to($user->email)->send(new \App\Mail\Bill($bill, 'Поступил счет ' . $bill->id . ' на утверждение от ' . $bill->user->name . '.'));
                    } catch (\Throwable $e) {
                        logger($e->getMessage());
                    }

                $bill->bill_alerts()->updateOrCreate([
                    'user_id' => $user->id
                ], [
                    'user_id' => $user->id,
                    'count' => 1
                ]);
            }
        }


        return response()->json($bill);
    }

    public function back(Bill $bill)
    {
        $log = $bill->bill_log;
        $bill->update($log->info);
        $bill->bill_action->create([
            'bill_id' => $bill->id,
            'user_id' => auth()->user()->id,
            'status' => $log->info['status'],
            'text' => 'Изменения были отменены',
        ]);
        $bill->alerts_count_inc();

        return redirect()->back();
    }

    public function store(Request $request, Bill $bill)
    {
        $chain = Chain::query()->find($request->chain_id);
        if ($chain->type == 1) {
            $request->validate([
                'text' => 'required',
                'files.*' => 'required|mimes:pdf'
            ]);
        } else {
            if (isset($request->files) and count($request->files) > 0) {
                $request->validate([
                    'text' => 'required',
                    'files.*' => 'required'
                ]);
            } else {
                $request->validate([
                    'text' => 'required',
                ]);
            }
        }


        $user = auth()->user();
        $files = [];

        if (isset($request->files)) {
            foreach ($request->files as $file) {
                if (!is_dir(public_path('files')))
                    mkdir(public_path('files'), 0777, TRUE);

                $filename = time() . rand(0, 1111111111111111111);
                $extension = $file->getClientOriginalExtension();

                File::put(public_path('files/' . $filename . '.' . $extension), file_get_contents($file));
                $files[] = 'files/' . $filename . '.' . $extension;

                $pdf = new Fpdi();
                if ($chain->type == 1)
                    try {
                        $pdf->setSourceFile('files/' . $filename . '.' . $extension);
                    } catch (\Throwable $e) {
                        $request->validate([
                            'badVersionFile' => 'required'
                        ]);
                    }
            }
        }
        $file = \App\Models\File::query()->create([
            'src' => $files,
        ]);
//        $bill->steps = 1;
//        dd($user);
//        if (in_array($user->user_role_id, [2, 7])) {
//            $bill->steps = 2;
//        }

        $bill->steps = 0;
        $bill->chain_id = $chain->id;
        $bill->user_role_id = $chain->value[$bill->steps];

        $bill->number = $request->number;
        if ($bill->number == "" or $bill->number == 0) {
            $bill->number = 0;
        }

        $bill->sum = str_replace(',', '.', $request->sum);
        if ($bill->sum == "" or $bill->sum == 0) {
            $bill->sum = 0;
        }
        $bill->client_id = $request->client_id;
        $bill->date = Carbon::parse($request->date)->toDateString();
        $bill->text = $request->text;
        $bill->file_id = $file->id;
        $bill->bill_type_id = BillType::query()->where('user_role_id', $chain->value[$bill->steps])->first()->id;

        if (is_null($bill->user_id))
            $bill->user_id = $user->id;

        $bill->save();

        $bill->bill_alerts()->create([
            'user_id' => $user->id
        ]);
        return redirect()->route($this->routes['view'], $bill);
    }

    public function accept()
    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);

        $user = auth()->user();
        $org_ids = $user->org_ids;
        $user_id = $user->id;
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);
        $bills = Bill::query()
            ->orderByDesc('created_at')->OrderBy('status')
            ->where('user_role_id', $user->user_role_id)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->where('status', 1)
            ->with(['user', 'bill_type', 'bill_status'])
            ->with('bill_alerts', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->with('chain', function ($query) {
                $query->where('type', 1);
            });
        if ($billNumber != 0 and $billNumber != "") {
            $bills = $bills->where("id", "=", $billNumber);
        }
        if ($contragentID != 0) {
            $bills = $bills->where("client_id", "=", $contragentID);
        }
        if ($billCreatorID != 0) {
            $bills = $bills->where("user_id", "=", $billCreatorID);
        }
        $bills = $bills->get();
        $header = 'Счета для подтверждения';
        $bill_type = 'счета';
        if (auth()->user()->read_only == true){
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '</div>';
        }else{
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
                '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
                '</div>';
        }
        return view($this->views['index'], compact('date_end', 'bill_type', 'org_ids', 'date_start', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function accepted()
    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);

        $user = auth()->user();
        $org_ids = $user->org_ids;
        $user_id = $user->id;
        $actions = BillAction::query()->where('user_id', $user->id)->pluck('bill_id')->toArray();
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);
        $bills = Bill::query()
            ->orderByDesc('created_at')->OrderBy('status')
            ->whereIn('id', $actions)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->with(['user', 'bill_type', 'bill_status'])
            ->with('bill_alerts', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->with('chain', function ($query) {
                $query->where('type', 1);
            })
            ->where('status', 1);
        if ($billNumber != 0 and $billNumber != "") {
            $bills = $bills->where("id", "=", $billNumber);
        }
        if ($contragentID != 0) {
            $bills = $bills->where("client_id", "=", $contragentID);
        }
        if ($billCreatorID != 0) {
            $bills = $bills->where("user_id", "=", $billCreatorID);
        }

        $bills = $bills->get();

        $header = 'Подтвержденные счета';
        $bill_type = 'счета';

        if (auth()->user()->read_only == true){
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '</div>';
        }else{
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
                '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
                '</div>';
        }
        return view($this->views['index'], compact('bill_type', 'org_ids', 'date_start', 'date_end', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function my()
    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);

        $user = auth()->user();
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);
        $user_id = $user->id;
        $bills = Bill::query()
            ->orderByDesc('created_at')->OrderBy('status')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$date_start, $date_end])
            ->with(['user', 'bill_type', 'bill_status', 'bill_actions'])
            ->with('bill_alerts', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->with('chain', function ($query) {
                $query->where('type', 1);
            });
        if ($billNumber != 0 and $billNumber != "") {
            $bills = $bills->where("id", "=", $billNumber);
        }
        if ($contragentID != 0) {
            $bills = $bills->where("client_id", "=", $contragentID);
        }
        if ($billCreatorID != 0) {
            $bills = $bills->where("user_id", "=", $billCreatorID);
        }

         $bills = $bills->where("status",1)->get();
        $org_ids = $user->org_ids;

        $header = 'Мои счета';
        $bill_type = 'счета';
        if (auth()->user()->read_only == true){
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '</div>';
        }else{
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
                '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
                '</div>';
        }
        return view($this->views['index'], compact('bill_type', 'date_start', 'org_ids', 'date_end', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function declined()

    {
        $billsCreators = User::query()->orderBy("name", "asc")->get();
        $contragents = Client::query()->orderBy("name", "asc")->get();

        $billCreatorID = \request("bill_creator_id", 0);
        $contragentID = \request("contragent_id", 0);
        $billNumber = \request("bill_number", 0);

        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['bill_date_start'] ?? now()->startOfYear())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['bill_end_start'] ?? now()->endOfYear())))->endOfDay();
        setcookie('bill_date_start', $date_start);
        setcookie('bill_end_start', $date_end);

        $user = auth()->user();
        if (is_null($user->remember_token))
            $user->update(['remember_token' => rand(111111111, 99999999999)]);


        $org_ids = $user->org_ids;
        $user_id = $user->id;
        $bills = Bill::query()
            ->orderBy('status')
            ->where("status",2)
//             ->where('user_role_id', $user->user_role_id)
//             ->orWhere('user_id', $user->id)
//             ->with(['user', 'bill_type', 'bill_status', 'bill_actions'])
//             ->with('bill_alerts', function ($query) use ($user_id) {
//                 $query->where('user_id', $user_id);
//             })->with('chain', function ($query) {
//                 $query->where('type', 2);
//             })
            ->orderBy('created_at', 'desc');
//         $bills = $bills->whereBetween('created_at', [$date_start, $date_end]);
//         if ($billNumber != 0 and $billNumber != "") {
//             $bills = $bills->where("id", "=", $billNumber);
//         }
//         if ($contragentID != 0) {
//             $bills = $bills->where("client_id", "=", $contragentID);
//         }
//         if ($billCreatorID != 0) {
//             $bills = $bills->where("user_id", "=", $billCreatorID);
//         }
        $bills = $bills->get();
        $header = 'Отклоненные счета';
        $bill_type = 'счета';
        if (auth()->user()->read_only == true){
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '</div>';
        }else{
            $action = '<div class="btn-group" style="float: right" role="group" aria-label="Basic example">' .
                '<a class="btn btn-success" href=' . route($this->routes['form'], ["type" => 1]) . ' style="float: right">Создать счет</a>' .
                '<a class="btn btn-primary ml-2" href=' . route($this->routes['form'], ["type" => 2]) . ' style="float: right">Создать заявку</a>' .
                '</div>';
        }

        return view($this->views['index'],
            compact('bill_type', 'org_ids', 'date_start', 'date_end', 'bills', 'user', 'header', 'action', 'billsCreators', 'contragents', 'billNumber', 'contragentID', 'billCreatorID'))->with('routes', $this->routes);
    }

    public function delete(Bill $bill)
    {
        $bill->delete();
        return redirect()->route('bill.index');
    }


    public function printBill(Bill $bill)
    {
        return view('bills.admin.print', compact('bill'));
    }
}
