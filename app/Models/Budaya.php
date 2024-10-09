<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budaya extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'budaya';
    public $incrementing = false;
    protected $keyType = 'uuid';

    public function koordinat()
    {
        return $this->hasMany(Koordinat::class);
    }
}
