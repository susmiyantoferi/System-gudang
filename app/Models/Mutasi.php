<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mutasi extends Model
{
    protected $table = "mutasis";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'jenis_mutasi',
        'jumlah',
        'tanggal'
    ];

    public function barangs(): BelongsTo{
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }

    public function users(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
