<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\StoreInterface;

use App\Contracts\Interfaces\Eloquent\UpdateInterface;

interface TransactionInterface extends StoreInterface, UpdateInterface
{
    public function getByReference(string $reference): mixed;
}
