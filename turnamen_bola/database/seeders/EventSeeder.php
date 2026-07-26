<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::updateOrCreate(
            ['name' => 'Piala Disdikpora Grassroot Regional Kebumen 2026'],
            [
                'name' => 'Piala Disdikpora Grassroot Regional Kebumen 2026',
                'organizer' => 'Dinas Pendidikan, Kepemudaan, dan Olahraga Kab. Kebumen',
                'location' => 'Stadion Chandradimuka Kebumen',
                'season' => '2026/2027',
                'description' => 'Turnamen sepak bola grassroot tingkat regional Kabupaten Kebumen.',
                'is_active' => true,
            ]
        );
    }
}
