<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topics extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'topics';
    public $incrementing = false;
    protected $keyType = 'uuid';

    public function categories() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function posts() {
        return $this->hasMany(Posts::class, 'topic_id', 'id');
    }
}
