<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillStatus;
use App\Models\BillType;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */
    public function run()
    {
        $userRoles = [
            'Директор',
            'Руководитель',
            'Бухгалтер',
            'Сис.Админ',
        ];
        foreach ($userRoles as $role)
            UserRole::query()->create([
                'name' => $role,
            ]);
        
        \App\Models\User::factory(20)->create();
        $billTypes = [
            'Директор' => 'Необходимо утверждение директора',
            'Руководитель' => 'Необходимо утверждение руководителя',
            'Бухгалтер' => 'Необходимо оплатить',
            'Бухгалтер ' => 'Оплачено',
        ];
        foreach ($billTypes as $name => $billType)
            BillType::query()->create([
                'short_name' => $name,
                'name' => $billType,
            ]);
        
        $billStatuses = [
            'На утверждении',
            'Утверждено директором',
            'Утверждено руководителем',
            'Оплачено бухгалтером',
            'Не утверждено директором',
            'Не утверждено руководителем',
        ];
        foreach ($billStatuses as $billStatus)
            BillStatus::query()->create([
                'name' => $billStatus,
            ]);
        Bill::factory(100)->create();
    }
}
