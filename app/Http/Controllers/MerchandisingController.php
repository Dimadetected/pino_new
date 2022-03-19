<?php

namespace App\Http\Controllers;

use App\Models\Merchandising;
use App\Models\Net;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MerchandisingController extends Controller
{
    private $views = [
        'index' => 'merchandising.index',
        'view' => 'merchandising.view',
        'form' => 'merchandising.form',
    ];
    private $routes = [
        'index' => 'merchandising.index',
        'delete' => 'merchandising.delete',
        'form' => 'merchandising.form',
        'store' => 'merchandising.store',
        'excel' => 'merchandising.excel',
    ];


    public function index()
    {
        $userID = \request('user_id',0);
        $netID = \request('net_id',0);
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['merch_date_start'] ?? now()->subMonth())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['merch_date_end'] ?? now()->addWeek())))->endOfDay();
        setcookie('merch_date_start', $date_start);
        setcookie('merch_date_end', $date_end);

        $nets = Net::query()->pluck("name","id");

        $merchLink = route($this->routes["excel"]) . "?" . ($_SERVER["REDIRECT_QUERY_STRING"]??"");

        $usersID = Merchandising::query()
            ->select("user_id")
            ->groupBy("user_id")
            ->pluck("user_id");

        $users = User::query()->whereIn("id",$usersID)->pluck("name","id");

        $merchandisings = Merchandising::query();

        if ($userID != 0){
            $merchandisings = $merchandisings->where('user_id',$userID);
        }
        if ($netID != 0){
            $merchandisings = $merchandisings->where('net_id',$netID);
        }

        $merchandisings = $merchandisings
            ->whereBetween('date', [$date_start, $date_end])
            ->with(["user", "net", "product"])
            ->get();

        $header = 'Мерчендайзинг';
        $action = '<a class="btn btn-success" href=' . route($this->routes['form']) . ' style="float: right">Создать</a>';
        return view($this->views['index'],
            compact('merchLink','header', 'action', 'merchandisings','date_end','date_start','users','nets','userID','netID'))->with('routes', $this->routes);
    }


    public function form(merchandising $merchandising)
    {

        $nets = Net::query()->get();
        $products = Product::query()->get();
        if (isset($merchandising) and isset($merchandising->date) and isset($merchandising->bottled_date)){
            $merchandising->date = Carbon::parse($merchandising->date)->format("d.m.Y");
            $merchandising->bottled_date = Carbon::parse($merchandising->bottled_date)->format("d.m.Y");
        }
        $header = '<a href="' . route($this->routes['index']) . '">Мерчендайзинг</a> / Редактирование';
        return view($this->views['form'],
            compact('header', 'merchandising', 'nets', 'products'))->with('routes', $this->routes);
    }

    public function delete(Merchandising $merchandising)
    {
        if (auth()->user()->user_role_id != 1) {
            abort(401);
        }

        Merchandising::query()->find($merchandising->id)->delete();
        return redirect()->route($this->routes['index']);
    }

    public function store(Request $request)
    {
        $data = [
            "name" => $request->name,
            "date" => Carbon::parse($request->date)->format("Y-m-d 00:00:00"),
            "net_id" => $request->net_id,
            "user_id" => $request->user_id,
            "address" => $request->address,
            "product_id" => $request->product_id,
            "balance" => $request->balance,
            "price" => $request->price,
            "bottled_date" => Carbon::parse($request->bottled_date)->format("Y-m-d 00:00:00"),
            "comment" => $request->comment,
            "photo_shelf" => "",
            "photo_tsd" => "",
            "photo_expiration_date" => "",
            "photo_price" => "",
        ];

        foreach ($request->files as $fileName => $file) {

            if (!is_dir(public_path('files')))
                mkdir(public_path('files'), 0777, TRUE);
            if (!is_dir(public_path('files/mechandising')))
                mkdir(public_path('files/mechandising'), 0777, TRUE);

            $extension = $file->getClientOriginalExtension();
            $path = 'files/mechandising/'. $fileName . time() . rand(0,1000000000000) . '.' . $extension;
            File::put(public_path($path), file_get_contents($file));

            $data[$fileName] = $path;
        }
        Merchandising::query()->updateOrCreate([
            "id" => $request->id
        ],$data);

        return redirect()->route($this->routes['index']);
    }
    public function createExcel(){
        $userID = \request('user_id',0);
        $netID = \request('net_id',0);
        $date_start = Carbon::parse(\request('date_start', ($_COOKIE['merch_date_start'] ?? now()->subMonth())))->startOfDay();
        $date_end = Carbon::parse(\request('date_end', ($_COOKIE['merch_date_end'] ?? now()->addWeek())))->endOfDay();
        setcookie('merch_date_start', $date_start);
        setcookie('merch_date_end', $date_end);


        $merchandisings = Merchandising::query();

        if ($userID != 0){
            $merchandisings = $merchandisings->where('user_id',$userID);
        }
        if ($netID != 0){
            $merchandisings = $merchandisings->where('net_id',$netID);
        }

        $merchandisings = $merchandisings
            ->whereBetween('date', [$date_start, $date_end])
            ->with(["user", "net", "product"])
            ->get();

        $document = new \PHPExcel();

        $sheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе

        $columnPosition = 0; // Начальная координата x
        $startLine = 1; // Начальная координата y

        $fields = [
            "Сеть",
            "Адрес",
            "Дата",
            "Торговый представитель",
            "Товар",
            "Остаток",
            "Цена",
            "Дата розлива",
            "Комментарий",
            "Фото Полка",
            "Фото ТСД",
            "Фото Срока годности",
            "Фото Цены"
        ];
        foreach ($fields as $fieldName){
            $sheet->setCellValueByColumnAndRow($columnPosition, $startLine, $fieldName);
            $columnPosition++;
        }

        foreach ($merchandisings as $m){
            $startLine++;
            $sheet->setCellValueByColumnAndRow(0, $startLine, $m->net->name);
            $sheet->setCellValueByColumnAndRow(1, $startLine, $m->address);
            $sheet->setCellValueByColumnAndRow(2, $startLine, Carbon::parse($m->date)->format("d.m.Y"));
            $sheet->setCellValueByColumnAndRow(3, $startLine, $m->user->name);
            $sheet->setCellValueByColumnAndRow(4, $startLine, $m->product->name);
            $sheet->setCellValueByColumnAndRow(5, $startLine, $m->balance." ");
            $sheet->setCellValueByColumnAndRow(6, $startLine, $m->price);
            $sheet->setCellValueByColumnAndRow(7, $startLine, Carbon::parse($m->bottled_date)->format("d.m.Y"));
            $sheet->setCellValueByColumnAndRow(8, $startLine, $m->comment);
            $sheet->setCellValueByColumnAndRow(9, $startLine, asset($m->photo_shelf));
            $sheet->setCellValueByColumnAndRow(10, $startLine, asset($m->photo_tsd));
            $sheet->setCellValueByColumnAndRow(11, $startLine, asset($m->photo_expiration_date));
            $sheet->setCellValueByColumnAndRow(12, $startLine, asset($m->photo_price));
        }

        $file = "merchandising.xlsx";

        $objWriter = new \PHPExcel_Writer_Excel2007($document);

        $objWriter->save($file);

        // Имя скачиваемого файла
//        header('Content-Type: application/vnd.ms-excel');
        header("Content-Type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Content-Length: ".filesize($file));
        header("Content-Disposition: attachment; filename=".$file);
        readfile($file);

        unlink($file);

        exit;

    }
}
