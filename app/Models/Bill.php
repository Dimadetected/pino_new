<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bill extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = [
        'chain',
    ];

    protected $casts = [
        'steps' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function bill_alert($id)
    {
        return $this->hasOne(BillAlert::class)->where('user_id', $id);
    }

    public function bill_alerts()
    {
        return $this->hasMany(BillAlert::class);
    }

    public function alerts_count_inc()
    {
        DB::transaction(function () {
            foreach ($this->bill_alerts as $alert) {
                $alert->update([
                    'count' => $alert->count + 1
                ]);
            }
        });
    }

    public function printFile()
    {
        $file = public_path('files/' . $this->id . '.pdf');
        if (is_file($file))
            return 'files/' . $this->id . '.pdf';
        return $this->file->src[0] ?? "";
    }

    public function mainUserAccept()
    {
        $actions = $this->bill_actions()->where('status', 1)->with('user')->get();
        if ($actions and count($actions) > 0) {
            foreach ($actions as $action)
                if (isset($action->user) and $action->user->user_role_id == 1)
                    return $action;
        }
        return FALSE;
    }

    public function bill_statuses()
    {
        return $this->hasMany(BillStatus::class, 'user_role_id', 'user_role_id');
    }

    public function bill_log()
    {
        return $this->hasOne(BillLog::class)->orderBy('id', 'desc');
    }

    public function goodNextStatus()
    {
        if ($this->steps == 1)
            return $this->chainOneGood();

        return $this->chainTwoGood();
    }

    /**
     * @return int|null
     * Цепочка без механика
     */
    private function chainOneGood()
    {
        switch ($this->bill_status_id) {
            case 1:
                return ['status' => 3,
                    'user_role_id' => 3];
            case 3:
                return ['status' => 8,
                    'user_role_id' => 1];
            case  8:
                return ['status' => 4,
                    'user_role_id' => 4];
            case 4:
                return ['status' => 10,
                    'user_role_id' => NULL];
        }
        return NULL;
    }

    /**
     * @return int|null
     * Цепочка с механиком
     */
    private function chainTwoGood()
    {
        switch ($this->bill_status_id) {
            case 1:
                return ['status' => 3,
                    'user_role_id' => 2];
            case 3:
                return ['status' => 6,
                    'user_role_id' => 1];
            case  6:
                return ['status' => 4,
                    'user_role_id' => 4];
            case 4:
                return ['status' => 10,
                    'user_role_id' => NULL];
        }
        return NULL;
    }

    public function badNextStatus()
    {
        if ($this->steps == 1)
            return $this->chainOneBad();

        return $this->chainTwoBad();
    }

    private function chainOneBad()
    {
        switch ($this->bill_status_id) {
            case 1:
                return 2;
            case 3:
                return 9;
            case  8:
                return 5;
            case 4:
                return 11;
        }
        return NULL;
    }

    private function chainTwoBad()
    {
        switch ($this->bill_status_id) {
            case 1:
                return 2;
            case 3:
                return 9;
            case  8:
                return 7;
            case 6:
                return 5;
            case 4:
                return 11;
        }
        return NULL;
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bill_type()
    {
        return $this->hasOne(BillType::class, 'user_role_id', 'user_role_id');
    }

    public function bill_status()
    {
        return $this->belongsTo(BillStatus::class);
    }

    public function bill_actions()
    {
        return $this->hasMany(BillAction::class)->orderBy('created_at', 'desc');
    }

    public function bill_action()
    {
        return $this->hasOne(BillAction::class)->orderBy('id', 'desc');
    }

    public function getActions(){
        $actions = $this->hasOne(BillAction::class)->orderBy('id', 'desc')->get();
        foreach ($actions as $k => $v ){
            $actions[$k]->new_date = Carbon::parse($actions[$k]->updated_at)->format("d.m.Y H:i");
        }

        $messages = $this->hasMany(Message::class, 'external_id', 'id')->where('type', 'bill')->get();
        foreach ($messages as $k => $v){
            $messages[$k]->new_date = Carbon::parse($messages[$k]->updated_at)->format("d.m.Y H:i");
            $file = \App\Models\File::query()->where('src', 'LIKE', '%messageID' . $v->id . '%')->first();
            $messages[$k]->images = [];
            if (isset($file->src) and count($file->src) > 0)
                $messages[$k]->images = $file->src;
        }

        return json_encode(array_merge(array($actions),array($messages)));
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'external_id', 'id')->where('type', 'bill');
    }

    public function chain()
    {
        return $this->belongsTo(Chain::class);
    }

}
