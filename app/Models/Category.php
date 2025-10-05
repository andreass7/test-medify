<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode'];

    public function items()
    {
        return $this->belongsToMany(MasterItem::class, 'category_master_item');
    }
}
