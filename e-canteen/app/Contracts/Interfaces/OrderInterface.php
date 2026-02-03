<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\CustomPaginationInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;

interface OrderInterface extends CustomPaginationInterface, UpdateInterface
{
    
}