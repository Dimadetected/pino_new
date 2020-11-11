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
            'Генеральный директор',
            'Главный механик',
            'Руководитель подразделения',
            'Бухгалтер',
            'Сис.Админ',
            'Сотрудник',
            'Механик',
        ];
        foreach ($userRoles as $role)
            UserRole::query()->create([
                'name' => $role,
            ]);
        
        \App\Models\User::factory(25)->create();
        $billTypes = [
            'Сотрудник' => [
                'text' => 'Необходимо подтвердить',
                'user_role_id' => 6
            ],
            'Генеральный директор' => [
                'text' => 'Необходимо утверждение генерального директора',
                'user_role_id' => 1
            ],
            'Главный механик' => [
                'text' => 'Необходимо утверждение главного механика',
                'user_role_id' => 2
            ],
            'Руководитель' => [
                'text' => 'Необходимо утверждение руководителя подразделения',
                'user_role_id' => 3
            ],
            'Бухгалтер' =>[
                'text' => 'Необходимо оплатить',
                'user_role_id' => 4
            ],
        ];
        foreach ($billTypes as $name => $billType)
            BillType::query()->create([
                'short_name' => $name,
                'name' => $billType['text'],
                'user_role_id' => $billType['user_role_id'],
            ]);
        
        $billStatuses = [
            'На подтверждении',
            'Не подтверждено',
            'На утверждении',
            'Утверждено генеральным директором',
            'Не утверждено генеральным директором',
            'Утверждено главным механиком',
            'Не утверждено главным механиком',
            'Утверждено руководителем подразделения',
            'Не утверждено руководителем подразделения',
            'Оплачено бухгалтером',
            'Не оплачено бухгалтером',
        ];
        foreach ($billStatuses as $billStatus)
            BillStatus::query()->create([
                'name' => $billStatus,
            ]);
        Bill::factory(100)->create();
    }
}
