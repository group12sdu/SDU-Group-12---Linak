<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    public const MIN_HEIGHT = 65;
    public const MAX_HEIGHT = 125;
    public const KINDS = ['standing', 'sitting'];

    protected $fillable = [
        'name', 'username', 'password', 'role',
        'standing_height', 'sitting_height',
        'desk_height', 'previous_desk_height',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'standing_height' => 'integer',
            'sitting_height' => 'integer',
            'desk_height' => 'integer',
            'previous_desk_height' => 'integer',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function preset(string $kind): ?int
    {
        return $this->{$kind.'_height'};
    }
}
