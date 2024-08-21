<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'kode' => $this->kode,
            'kategori' => $this->kategori,
            'lokasi' => $this->lokasi,
            'harga' => $this->harga,
            'jumlah' => $this->jumlah,
        ];
    }
}
