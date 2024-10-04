<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Space;

class SpaceSeeder extends Seeder {

    public function run(): void{
        Space::factory(10)->create();
    }
}
