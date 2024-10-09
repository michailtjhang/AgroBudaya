<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Koordinat extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'koordinat';
    public $incrementing = false;
    protected $keyType = 'uuid';

    public function budaya()
    {
        return $this->belongsTo(Budaya::class, 'nama_budaya', 'nama_budaya');
    }
}
