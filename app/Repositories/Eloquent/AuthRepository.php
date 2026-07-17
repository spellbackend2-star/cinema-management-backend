<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }


    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }


    public function getResetToken(string $email): ?object
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();
    }


    public function updatePassword(User $user, string $password): User
    {
        $user->update([
            'password' => Hash::make($password),
        ]);

        return $user->fresh();
    }


    public function deleteResetToken(string $email): bool
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete() > 0;
    }
}