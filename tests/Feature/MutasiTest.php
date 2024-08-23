<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Mutasi;
use Database\Seeders\BarangSeeder;
use Database\Seeders\MutasiSeeder;
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
                    ['jenis_mutasi' => 'test',
                    'jumlah' => 1,
                    'tanggal' => '2024-08-22',]
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

        $this->post('/api/barangs/' . ($barang->id + 3) . '/mutasis',
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

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $mutasi = Mutasi::query()->limit(1)->first();

        $this->get('/api/barangs/' . $mutasi->barang_id . '/mutasis/' . $mutasi->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    ['jenis_mutasi' => 'test masuk',
                        'jumlah' => 3,
                        'tanggal' => '2024-08-22',]
                ]
            ]);
    }

    public function testGetFailled()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $mutasi = Mutasi::query()->limit(1)->first();

        $this->get('/api/barangs/' . $mutasi->barang_id . '/mutasis/' . ($mutasi->id + 1), [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $mutasi = Mutasi::query()->limit(1)->first();

        $this->put('/api/barangs/' . $mutasi->barang_id . '/mutasis/' . $mutasi->id,
            [
                'jenis_mutasi' => 'update ',
                'jumlah' => 3,
                'tanggal' => '2024-09-30',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    ['jenis_mutasi' => 'update',
                    'jumlah' => 3,
                    'tanggal' => '2024-09-30',]
                ]
            ]);
    }

    public function testUpdateFailled()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $mutasi = Mutasi::query()->limit(1)->first();

        $this->put('/api/barangs/' . $mutasi->barang_id . '/mutasis/' . $mutasi->id,
            [
                'jenis_mutasi' => '',
                'jumlah' => 'aidh',
                'tanggal' => '',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'jenis_mutasi' => [
                        'The jenis mutasi field is required.'
                    ],
                    'jumlah' => [
                        'The jumlah field must be a number.'
                    ],
                    'tanggal' => [
                        'The tanggal field is required.'
                    ],

                ]
            ]);
    }

    public function testUpdateNotFound()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $mutasi = Mutasi::query()->limit(1)->first();

        $this->put('/api/barangs/' . $mutasi->barang_id . '/mutasis/' . ($mutasi->id + 1),
            [
                'jenis_mutasi' => 'update ',
                'jumlah' => 3,
                'tanggal' => '2024-09-30',
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $mutasi = Mutasi::query()->limit(1)->first();

        $this->delete('/api/barangs/' . $mutasi->barang_id . '/mutasis/' . $mutasi->id,
            [

            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                "data" => true
            ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $mutasi = Mutasi::query()->limit(1)->first();

        $this->delete('/api/barangs/' . $mutasi->barang_id . '/mutasis/' . ($mutasi->id + 1),
            [

            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testListSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->get('/api/barangs/' . $barang->id . '/mutasis',
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        'jenis_mutasi' => 'test masuk',
                        'jumlah' => 3,
                        'tanggal' => '2024-08-22',
                    ]
                ]
            ]);
    }

    public function testListBarangNotFound()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);
        $barang = Barang::query()->limit(1)->first();

        $this->get('/api/barangs/' . ($barang->id + 1) . '/mutasis',
            [
                'Authorization' => 'test'
            ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }

    public function testAllDataSuccess()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);

        $this->get('/api/barangs/mutasis',
            [
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        'jenis_mutasi' => 'test masuk',
                        'jumlah' => 3,
                        'tanggal' => '2024-08-22',
                    ]
                ]
            ]);
    }

    public function testAllDataTokenFailled()
    {
        $this->seed([UserSeeder::class, BarangSeeder::class, MutasiSeeder::class]);

        $this->get('/api/barangs/mutasis',
            [
                'Authorization' => 'salah'
            ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        'unauthorized'
                    ]
                ]
            ]);
    }

}
