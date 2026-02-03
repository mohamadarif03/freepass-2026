<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\CustomPaginationInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;

interface ReviewInterface extends StoreInterface, UpdateInterface, DeleteInterface, CustomPaginationInterface {}
