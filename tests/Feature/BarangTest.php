<?php

namespace Tests\Feature;

use App\Models\Barang;
use Database\Seeders\BarangSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class BarangTest extends TestCase
{
    public function testCreateBarangSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/barangs', [
            'name' => 'iref',
            'kategori' => 'makanan',
            'lokasi' => 'rak atas',
            'harga' => 1000,
            'jumlah' => 5
        ], [
            'Authorization' => 'test'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'iref',
                    'kategori' => 'makanan',
                    'lokasi' => 'rak atas',
                    'harga' => 1000,
                    'jumlah' => 5
                ]
            ]);
    }

    public function testCreateBarangFailled()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/barangs', [
            'name' => '',
            'kategori' => '',
            'lokasi' => 'rak atas',
            'harga' => 'asd',
            'jumlah' => 5
        ], [
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        "The name field is required."
                    ],
                    'kategori' => [
                        "The kategori field is required."
                    ],
                    'harga' => [
                        "The harga field must be a number."
                    ]
                ]
            ]);
    }

    public function testCreateBarangUnauthorize()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/barangs', [
            'name' => 'iwyieu',
            'kategori' => 'ksjdsd',
            'lokasi' => 'rak atas',
            'harga' => 5000,
            'jumlah' => 5
        ], [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        "unauthorized"
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->get('/api/barangs/' . $barang->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'testbarang',
                    'kode' => 'test',
                    'kategori' => 'test',
                    'lokasi' => 'test',
                    'harga' => 1000,
                    'jumlah' => 7,
                ]
            ]);
    }

    public function testGetFailled()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->get('/api/barangs/' . $barang->id + 1, [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testGetOtherUserBarang()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->get('/api/barangs/' . $barang->id, [
            'Authorization' => 'test2'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->put('/api/barangs/' . $barang->id, [
            'name' => 'update',
            'kategori' => 'update',
            'lokasi' => 'update',
            'harga' => 10,
            'jumlah' => 1,
        ], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'update',
                    'kategori' => 'update',
                    'lokasi' => 'update',
                    'harga' => 10,
                    'jumlah' => 1,
                ]
            ]);
    }

    public function testUpdateValidationErrors()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->put('/api/barangs/' . $barang->id, [
            'name' => '',
            'kategori' => 'update',
            'lokasi' => 'update',
            'harga' => 'asdd',
            'jumlah' => 1,
        ], [
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    "name" => [
                        "The name field is required."
                    ],
                    "harga" => [
                        "The harga field must be a number."
                    ]
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->delete('/api/barangs/' . $barang->id, [], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->delete('/api/barangs/' . ($barang->id + 1), [], [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

}
