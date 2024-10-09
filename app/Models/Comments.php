<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comments extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];
    protected $table = 'comments';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = ['post_id', 'user_id', 'parent_id', 'content'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function post() {
        return $this->belongsTo(Posts::class, 'post_id', 'id');
    }

    public function replies() {
        return $this->hasMany(Comments::class, 'parent_id', 'id');
    }
}
