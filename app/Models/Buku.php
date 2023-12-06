<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id';
    protected $fillable = ['judul', 'penulis', 'tgl_terbit', 'harga', 'filename', 'filepath'];
    protected $dates = ['tgl_terbit'];

    public function galleries() : HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function favourites() : HasMany
    {
        return $this->hasMany(Favourite::class);
    }

    public function photos()
    {
        return $this->hasMany('App\Buku', 'id_buku', 'id');
    }
}
