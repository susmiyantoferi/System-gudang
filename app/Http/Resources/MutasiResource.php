<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MutasiResource extends JsonResource
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
            'jenis_mutasi' => $this->jenis_mutasi,
            'jumlah' => $this->jumlah,
            'tanggal' => $this->tanggal,
            'barang_id' => $this->barang_id,
            'barang' => new BarangResource($this->whenLoaded('barangs')),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('users')),
        ];
    }
}
