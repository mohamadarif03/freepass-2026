<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\AuthInterface;
use App\Contracts\Repositories\BaseRepository;
use App\Models\User;

class AuthRepository extends BaseRepository implements AuthInterface
{
    public function __construct(User $user)
    {
       $this->model = $user;
    }
    
    public function store(array $data): mixed
    {   
        return $this->model->create($data);
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->findOrFail($id)->update($data);
    }
}