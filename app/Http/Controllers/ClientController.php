<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Services\ClientService;
use App\Models\Client;
use App\Models\Organisation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->service = new ClientService();
    }

    public function index()
    {
        $header = '<div class="row"><div class="col-6">Контрагенты</div> <div class="col-6 text-right"><a class="btn btn-success" href="' . route('clients.form') . '"> Новый </a></div> </div>';
        return view('clients.admin.index', compact('header'));
    }


    public function form(Client $client)
    {
        $header = '<a href="' . route('clients.index') . '"> Контрагенты </a> / Форма контрагента';
        $id = $user->id ?? 0;

        $files = [];
        foreach ($client->file_id as $file)
            $files[$file['file_id']] = \App\Models\File::query()->find($file['file_id']);

        return view('clients.admin.form', compact('client', 'header', 'id', 'files'));
    }

    public function view(Client $client)
    {
        $header = '<a href="' . route('clients.index') . '"> Контрагенты </a> / Данные контрагента';
        $id = $user->id ?? 0;

        $files = [];
        foreach ($client->file_id as $file)
            if (isset($file['file_id']))
                $files[$file['file_id']] = \App\Models\File::query()->find($file['file_id']);

        return view('clients.admin.view', compact('client', 'header', 'id', 'files'));
    }

    public function contracts()
    {
        $header = '<a href="' . route('clients.index') . '"> Контрагенты </a> / Контракты';

        $files = \App\Models\File::query()->pluck('src','id')->toArray();

        $clients = Client::query()->whereNotNull('file_id')->get();

        return view('clients.admin.contracts',compact('header','files','clients'));
    }

    public function store(ClientRequest $request, Client $client)
    {
        $fileArr = [];
        $j = 0;
        foreach ($request->ids as $key => $id) {
            $files = [];
            if ($id == 0) {
                if (!isset($request->file()['files'][$j]))
                    continue;

                $file = $request->file()['files'][$j];
                if (!is_dir(public_path('clients_files')))
                    mkdir(public_path('clients_files'), 0777, TRUE);

                $filename = time() . rand(0, 1111111111111111111);
                $extension = $file->getClientOriginalExtension();

                File::put(public_path('clients_files/' . $filename . '.' . $extension), file_get_contents($file));
                $files[] = 'clients_files/' . $filename . '.' . $extension;

                $file = \App\Models\File::query()->create([
                    'src' => $files,
                ]);
                $fileArr[] = ['file_id' => $file->id, 'numb' => ($request->numbers[$key] ?? ""), 'date' => Carbon::parse(($request->dates[$key]) ?? now())->format('Y-m-d')];
                $j++;
            } else {
                $info = [];
                foreach ($client->file_id as $file)
                    if ($file['file_id'] == $id) {
                        $info = $file;
                        break;
                    }
                $fileArr[] = $info;
            }
        }
        if (empty($fileArr))
            $fileArr = $client->file_id;

        $arr = array_merge($request->except([0, 1]), ['file_id' => $fileArr]);
        if (isset($client->id))
            $client = $this->service->update($client->id, $arr);
        else
            $client = $this->service->store($arr);


        return redirect()->route('clients.view', $client->id);
    }


}
