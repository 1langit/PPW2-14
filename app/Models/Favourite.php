<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Favourite extends Model
{
    use HasFactory;

    protected $table = 'favourite';
    protected $fillable = ['id', 'user_id', 'buku_id'];
}
