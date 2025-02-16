<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;
    protected $table = 'transaksi_detail';
    protected $fillable = ['transaksi_header_id', 'lantai_id', 'meja_id', 'harga', 'user_id'];

    public function header()
    {
        return $this->belongsTo(TransaksiHeader::class, 'transaksi_header_id');
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id');
    }

    public function lantai()
    {
        return $this->belongsTo(Lantai::class, 'lantai_id');
    }
}
