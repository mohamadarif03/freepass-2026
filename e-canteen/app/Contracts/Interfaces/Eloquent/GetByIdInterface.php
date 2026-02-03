<?php

namespace App\Contracts\Interfaces\Eloquent;

interface GetByIdInterface
{
    public function getById(mixed $id): mixed;
}
