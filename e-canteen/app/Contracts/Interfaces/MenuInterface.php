<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\CustomPaginationInterface;
use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetByIdInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use Illuminate\Http\Request;

interface MenuInterface extends StoreInterface, UpdateInterface, CustomPaginationInterface, DeleteInterface, GetByIdInterface
{
    public function getByCanteenId(Request $request,int $canteenId): mixed;
}
