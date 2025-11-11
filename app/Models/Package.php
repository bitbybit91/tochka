<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'packages';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'type',
        'item_uuid',
        'latitude',
        'longitude',
        'country_name_en_shipping_from',
        'country_name_en_shipping_to',
        'drop_city_id',
        'city_metro_station_uuid',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
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

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_uuid', 'uuid');
    }

    public function price()
    {
        return $this->hasOne(PackagePrice::class, 'uuid', 'uuid');
    }

    public function getPrice($currency = 'USD')
    {
        $price = $this->price;
        if (!$price) {
            return 0;
        }
        // Add currency conversion logic here if needed
        return $price->price;
    }
}
