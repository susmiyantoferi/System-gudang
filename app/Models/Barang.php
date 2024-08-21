<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = "barangs";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'name',
        'kode',
        'ketegori',
        'lokasi',
        'harga',
        'jumlah',
    ];

    public function users(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mutasis(): HasMany{
        return $this->hasMany(Mutasi::class, 'barang_id', 'id');
    }
}
