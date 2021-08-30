<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;

trait CreateUniqueSlug
{
    public function getUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);

        $userFound = User::where('slug', $slug)->count();

        if ($userFound > 0) {
            return $slug . '-' . $userFound;
        }

        return $slug;
    }
}
