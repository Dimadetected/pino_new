<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractFormRequest;
use App\Http\Services\ContractService;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ContractController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new ContractService();
    }

    public function index()
    {
        $header = '<a href="' . route('contracts.index') . '"> Контракты  </a> ';

        $contracts = $this->service->get();

        return view('contracts.admin.index', compact('header', 'contracts'));
    }

    public function show(Contract $contract)
    {
        $header = '<a href="' . route('contracts.index') . '"> Контракты  </a> / Просмотр';

        return view('contracts.admin.view', compact('header', 'contract'));
    }

    public function form(Contract $contract)
    {
        if (in_array(auth()->user()->id, Contract::IDS) or $contract->user_id == auth()->user()->id) {

            if (\request('type') == 1)
                $btn = "Утвердить";
            else
                $btn = "Изменить";

            $header = '<a href="' . route('contracts.index') . '"> Контракты  </a> / Добавить';
            $clients = Client::query()->pluck("name", "id")->toArray();

            return view('contracts.admin.form', compact('header', 'contract', 'clients', 'btn'));
        } else {
            abort(401);
        }
    }

    public function store(Contract $contract, ContractFormRequest $request)
    {
        $data = $request->except(['files', '_token']);
        $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->toDateString();
        if (isset($request->file()['files'])) {
            $file = $request->file()['files'];

            if (!is_dir(public_path('clients_files')))
                mkdir(public_path('clients_files'), 0777, TRUE);

            $filename = time() . rand(0, 1111111111111111111);
            $extension = $file->getClientOriginalExtension();

            File::put(public_path('clients_files/' . $filename . '.' . $extension), file_get_contents($file));
            $files[] = 'clients_files/' . $filename . '.' . $extension;
            $file = \App\Models\File::query()->create([
                'src' => $files,
            ]);

            $data['file_id'] = $file->id;
        }
        if ($request->type == 1)
            $data['status'] = $contract->nextStep();
        if (!isset($contract->user_id))
            $data['user_id'] = auth()->user()->id;

        if (isset($contract->id)) {
            $contract = $this->service->update($contract->id, $data);
        } else {
            $contract = $this->service->store($data);
        }

        ContractLog::query()->create(['log' => $contract->toArray(), 'user_id' => auth()->user()->id]);

        return redirect()->route('contracts.show', $contract);
    }

}
