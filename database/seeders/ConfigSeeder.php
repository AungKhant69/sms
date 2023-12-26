<?php

namespace Database\Seeders;

use App\Models\ConfigModel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConfigModel::create([
            'name' => 'user1',
            'paginate' => 10,
            'status' => 1,
        ]);
    }
}
