<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@gmail.com')->first();
        Barang::create([
            'name' => 'testbarang',
            'kode' => 'test',
            'kategori' => 'test',
            'lokasi' => 'test',
            'harga' => 1000,
            'jumlah' => 7,
            'user_id' => $user->id
        ]);
    }
}
