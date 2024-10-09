<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'categories';
    public $incrementing = false;
    protected $keyType = 'uuid';

    public function topics() {
        return $this->hasMany(Topics::class, 'category_id', 'id');
    }
}
