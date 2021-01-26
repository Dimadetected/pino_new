<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Services\ClientService;
use App\Models\Client;
use App\Models\Organisation;
use App\Models\User;
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
        return view('clients.admin.form', compact('client', 'header', 'id'));
    }

    public function view(Client $client)
    {
        $header = '<a href="' . route('clients.index') . '"> Контрагенты </a> / Данные контрагента';
        $id = $user->id ?? 0;
        return view('clients.admin.view', compact('client', 'header', 'id'));
    }

    public function store(ClientRequest $request, Client $client)
    {
        $files = [];
        if (isset($request->file()['files'])) {
            foreach ($request->file()['files'] as $file) {
                if (!is_dir(public_path('clients_files')))
                    mkdir(public_path('clients_files'), 0777, TRUE);

                $filename = time() . rand(0, 1111111111111111111);
                $extension = $file->getClientOriginalExtension();

                File::put(public_path('clients_files/' . $filename . '.' . $extension), file_get_contents($file));
                $files[] = 'clients_files/' . $filename . '.' . $extension;
            }
            $file = \App\Models\File::query()->create([
                'src' => $files,
            ]);
        } else {
            $file = (object)['id' => $client->file_id];
        }

        $arr = array_merge($request->except([0, 1]), ['file_id' => $file->id]);
        if (isset($client->id))
            $client = $this->service->update($client->id, $arr);
        else
            $client = $this->service->store($arr);


        return redirect()->route('clients.view',$client->id);
    }


}
