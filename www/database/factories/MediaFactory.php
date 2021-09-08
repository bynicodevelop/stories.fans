<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $orientation = ["portrait", "landscape"];

        return [
            "name_preview" => null,
            "name" => $this->faker->word(),
            "ext" => $this->faker->fileExtension(),
            "type" => $this->faker->word(),
            "orientation" => $orientation[array_rand($orientation)],
        ];
    }
}
