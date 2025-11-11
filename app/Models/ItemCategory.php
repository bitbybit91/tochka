<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $table = 'item_categories';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'icon',
        'parent_id',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'item_category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ItemCategory::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(ItemCategory::class, 'parent_id', 'id');
    }
}
