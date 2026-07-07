<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $fillable = ['nama', 'jenis_kelamin', 'tanggal_lahir', 'nama_ibu', 'user_id'];

    public function records()
    {
        return $this->hasMany(GrowthRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
