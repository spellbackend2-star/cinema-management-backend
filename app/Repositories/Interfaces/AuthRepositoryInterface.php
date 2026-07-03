<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function create(array $data);

    public function findByEmail(string $email);

    public function getResetToken(string $email);



    public function updatePassword(User $user, string $password):User;
    

    public function deleteResetToken(string $email);
}