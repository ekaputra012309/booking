<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;
    protected $table = 'meja';
    protected $fillable = ['nama_meja', 'harga', 'lantai_id', 'status_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }

    public function status()
    {
        return $this->belongsTo(StatusBooking::class);
    }
}
