<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'item_category_id',
        'user_uuid',
        'is_promoted',
        'number_of_sales',
        'number_of_views',
        'reviewed_by_user_uuid',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_promoted' => 'boolean',
            'number_of_sales' => 'integer',
            'number_of_views' => 'integer',
            'reviewed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id', 'id');
    }

    public function packages()
    {
        return $this->hasMany(Package::class, 'item_uuid', 'uuid');
    }

    public function reviews()
    {
        return $this->hasMany(RatingReview::class, 'item_uuid', 'uuid');
    }

    public function getMinPrice()
    {
        return $this->packages()->min('price');
    }

    public function getAverageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function incrementViews()
    {
        $this->increment('number_of_views');
    }
}
