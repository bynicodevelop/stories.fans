<?php

namespace Database\Factories;

use App\Models\InvitationStat;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitationStatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvitationStat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "stats" => '{"referer":"instagram"}'
        ];
    }
}
