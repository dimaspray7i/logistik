<?php

namespace Database\Seeders;

use App\Models\ExpeditionProvider;
use Illuminate\Database\Seeder;

class ExpeditionProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'name'        => 'AEI — PT. Antar Exprindo Indah',
                'code'        => 'AEI',
                'description' => 'Ekspedisi mitra utama perusahaan.',
                'is_active'   => true,
            ],
            [
                'name'        => 'JNE — Jalur Nugraha Ekakurir',
                'code'        => 'JNE',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'J&T Express',
                'code'        => 'JT',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'SiCepat Ekspres',
                'code'        => 'SICEPAT',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Pos Indonesia',
                'code'        => 'POS',
                'description' => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'DHL Express',
                'code'        => 'DHL',
                'description' => null,
                'is_active'   => false,
            ],
            [
                'name'        => 'FedEx',
                'code'        => 'FEDEX',
                'description' => null,
                'is_active'   => false,
            ],
        ];

        foreach ($providers as $data) {
            ExpeditionProvider::firstOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
