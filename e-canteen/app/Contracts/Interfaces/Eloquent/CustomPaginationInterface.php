<?php

namespace App\Contracts\Interfaces\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomPaginationInterface
{
    public function customPaginate(Request $request, int $pagination = 10): LengthAwarePaginator;
}
