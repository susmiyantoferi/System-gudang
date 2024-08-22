<?php

namespace Tests\Feature;

use App\Models\Barang;
use Database\Seeders\BarangSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MutasiTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->post('/api/barangs/' . $barang->id . '/mutasis',
            [
                'jenis_mutasi' => 'test',
                'jumlah' => 1,
                'tanggal' => '2024-08-22',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(201)
            ->assertJson([
                'data' => [
                    'jenis_mutasi' => 'test',
                    'jumlah' => 1,
                    'tanggal' => '2024-08-22',
                ]
            ]);
    }

    public function testCreatedFailled()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->post('/api/barangs/' . $barang->id . '/mutasis',
            [
                'jenis_mutasi' => '',
                'jumlah' => 'qewe',
                'tanggal' => 'sjfjf',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'jenis_mutasi' => [
                        "The jenis mutasi field is required."
                    ],
                    'jumlah' => [
                        "The jumlah field must be a number."
                    ],
                    'tanggal' => [
                        "The tanggal field must be a valid date."
                    ],

                ]
            ]);
    }

    public function testCreateBarangNotFound()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->post('/api/barangs/' . ($barang->id+3) . '/mutasis',
            [
                'jenis_mutasi' => 'test',
                'jumlah' => 5,
                'tanggal' => '2024-08-22',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    'message' => [
                        "not found"
                    ]
                ]
            ]);
    }


}
