<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'username',
        'passphrase_hash',
        'registration_date',
        'last_login_date',
        'language',
        'currency',
        'bitcoin',
        'bitmessage',
        'tox',
        'email',
        'pgp',
        'description',
        'long_description',
        'invite_code',
        'two_factor_authentication',
        'has_top_banner',
        'banned',
        'possible_scammer',
        'vacation_mode',
        'has_avatar',
        'is_seller',
        'is_trusted_seller',
        'is_tester',
        'is_admin',
        'is_staff',
    ];

    protected $hidden = [
        'passphrase_hash',
        'two_factor_authentication',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'datetime',
            'last_login_date' => 'datetime',
            'two_factor_authentication' => 'boolean',
            'has_top_banner' => 'boolean',
            'banned' => 'boolean',
            'possible_scammer' => 'boolean',
            'vacation_mode' => 'boolean',
            'has_avatar' => 'boolean',
            'is_seller' => 'boolean',
            'is_trusted_seller' => 'boolean',
            'is_tester' => 'boolean',
            'is_admin' => 'boolean',
            'is_staff' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            if (empty($model->invite_code)) {
                $model->invite_code = (string) Str::uuid();
            }
        });
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'user_uuid', 'uuid');
    }

    public function isSeller(): bool
    {
        return (bool) $this->is_seller;
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function getAvatarUrl(): string
    {
        if ($this->has_avatar) {
            return asset("data/images/{$this->uuid}_av.jpeg");
        }
        return asset('images/default-avatar.png');
    }
}
