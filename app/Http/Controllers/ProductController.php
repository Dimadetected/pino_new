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
use App\Models\Net;
use App\Models\Product;
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

class ProductController extends Controller
{
    private $views = [
        'index' => 'product.index',
        'view' => 'product.view',
        'form' => 'product.form',
    ];
    private $routes = [
        'index' => 'product.index',
        'delete' => 'product.delete',
        'form' => 'product.form',
        'store' => 'product.store',
    ];


    public function index()
    {
//        if (auth()->user()->user_role_id != 1){
//            abort(401);
//        }

        $products = Product::all();
        $header = 'Товары';
        $action = '<a class="btn btn-success" href=' . route($this->routes['form']) . ' style="float: right">Создать</a>';
        return view($this->views['index'],
            compact('header', 'action','products'))->with('routes', $this->routes);
    }

    public function delete(Product $product)
    {
//        if (auth()->user()->user_role_id != 1){
//            abort(401);
//        }

        Product::query()->find($product->id)->delete();
        return redirect()->route($this->routes['index']);
    }

    public function form(Product $product)
    {
//        if (auth()->user()->user_role_id != 1){
//            abort(401);
//        }

        $header = '<a href="' . route($this->routes['index']) . '">Товары</a> / Редактирование';
        return view($this->views['form'],
            compact('header', 'product'))->with('routes', $this->routes);
    }

    public function store(Request $request)
    {
//        if (auth()->user()->user_role_id != 1){
//            abort(401);
//        }

        $request->validate([
            "name" => "required"
        ]);

        Product::query()->updateOrCreate([
            "id" => $request->id
        ], [
            "name" => $request->name,
        ]);

        return redirect()->route($this->routes['index']);
    }
}
