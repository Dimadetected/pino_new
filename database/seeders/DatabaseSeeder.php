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
            [
                'text' => 'Подтверждено',
                'user_role_id' => 6,
                'status' => 'good'
            ],
            [
                'text' => 'Не подтверждено',
                'user_role_id' => 6,
                'status' => 'bad'
            ],
            [
                'text' => 'Утверждено генеральным директором',
                'user_role_id' => 1,
                'status' => 'good'
            ],
            [
                'text' => 'Не утверждено генеральным директором',
                'user_role_id' => 1,
                'status' => 'bad'
            ],
            [
                'text' => 'Утверждено главным механиком',
                'user_role_id' => 2,
                'status' => 'good'
            ],
            [
                'text' => 'Не утверждено главным механиком',
                'user_role_id' => 2,
                'status' => 'bad'
            ],
            [
                'text' => 'Утверждено руководителем подразделения',
                'user_role_id' => 3,
                'status' => 'good'
            ],
            [
                'text' => 'Не утверждено руководителем подразделения',
                'user_role_id' => 3,
                'status' => 'bad'
            ],
            [
                'text' => 'Оплачено бухгалтером',
                'user_role_id' => 4,
                'status' => 'good'
            ],
            [
                'text' => 'Не оплачено бухгалтером',
                'user_role_id' => 4,
                'status' => 'bad'
            ],
        ];
        foreach ($billStatuses as $billStatus)
            BillStatus::query()->create([
                'name' => $billStatus['text'],
                'status' => $billStatus['status'],
                'user_role_id' => $billStatus['user_role_id'],
            ]);
        Bill::factory(100)->create();
    }
}
