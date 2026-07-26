<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use Illuminate\Database\Seeder;

class AgeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'KU-10', 'max_birth_year' => 2016, 'min_birth_year' => null, 'is_active' => true],
            ['name' => 'KU-12', 'max_birth_year' => 2014, 'min_birth_year' => null, 'is_active' => true],
        ];

        foreach ($categories as $category) {
            AgeCategory::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
