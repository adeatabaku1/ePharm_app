<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;

class PharmacySeeder extends Seeder
{
    public function run()
    {
        Tenant::create([
            'name' => 'Medico Pharmacy',
            'email' => 'medico@pharmacy.com',
            'phone' => '123-456-7890',
            'address' => '123 Main Street'
        ]);
    }
}

