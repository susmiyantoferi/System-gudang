<?php

namespace Tests\Feature;

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
            'harga' => 1000,
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
                    ]
                ]
            ]);
    }


}
