<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posts extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'posts';
    public $incrementing = false;
    protected $keyType = 'uuid';

    public function topics() {
        return $this->belongsTo(Topics::class, 'topic_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments() {
        return $this->hasMany(Comments::class, 'post_id', 'id');
    }
}
