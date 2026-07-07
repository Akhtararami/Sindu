<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrowthRecord extends Model
{
    protected $fillable = ['child_id', 'tanggal_periksa', 'umur_bulan', 'berat_badan', 'tinggi_badan', 'status_gizi', 'keluhan', 'solusi'];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
