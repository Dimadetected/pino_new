<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::query()->get()->random()->id,
            'text' => $this->faker->realText(),
            'bill_type_id' => 1,
            'bill_status_id' => 1,
            'user_role_id' => 6,
            'steps' => 1
         ];
    }
}
