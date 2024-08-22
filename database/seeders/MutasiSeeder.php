<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Mutasi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MutasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barang = Barang::query()->limit(1)->first();
        $user = User::query()->limit(1)->first();
        Mutasi::create([
            'jenis_mutasi' => 'test masuk',
            'jumlah' => 3,
            'tanggal' => '2024-08-22',
            'barang_id' => $barang->id,
            'user_id' => $user->id,
        ]);
    }
}
