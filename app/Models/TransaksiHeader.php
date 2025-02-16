<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiHeader extends Model
{
    use HasFactory;
    protected $table = 'transaksi_header';
    protected $fillable = ['user_id', 'invoice_number', 'status_transaksi', 'checkin', 'checkout', 'approve_time', 'approveby', 'dp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_header_id', 'id');
    }
}
