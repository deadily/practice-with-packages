<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'full_name',
        'login',
        'password_hash',
        'role_id'
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->password_hash = md5($user->password_hash);
        });
    }

    public function findIdentity(int $id)
    {
        return static::where('id', $id)->first();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function attemptIdentity(array $credentials)
    {
        return self::where([
            'login' => $credentials['login'],
            'password_hash' => md5($credentials['password'])
        ])->first();
    }

    public function isAdmin(): bool
    {
        return $this->role_id == 1;
    }
}