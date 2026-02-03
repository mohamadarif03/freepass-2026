<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Interfaces\MenuInterface;
use App\Helpers\ResponseHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Http\Requests\MenuUpdateRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Service\MenuService;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    use PaginationTrait;
    private MenuInterface $menu;
    private MenuService $menuService;

    public function __construct(MenuInterface $menu, MenuService $menuService)
    {
        $this->menu = $menu;
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        $menus = $this->menu->customPaginate($request, 10);
        $data['paginate'] = $this->customPaginate($menus->currentPage(), $menus->lastPage());
        $data['data'] = MenuResource::collection($menus);
        return ResponseHelper::success($data, 'Menu loaded successfully');
    }

    public function store(MenuRequest $request)
    {
        $data = $this->menuService->handleStore($request);
        $data['canteen_id'] = Auth::user()->canteen->id;
       
        $data = $this->menu->store($data);
        return ResponseHelper::success(MenuResource::make($data), 'Menu created successfully');
    }

    public function show(Menu $menu)
    {
        return ResponseHelper::success(MenuResource::make($menu), 'Menu loaded successfully');
    }

    public function update(MenuUpdateRequest $request, Menu $menu)
    {
        $data = $this->menuService->handleUpdate($request, $menu);
        $this->menu->update($menu->id, $data);
        return ResponseHelper::success(MenuResource::make($menu->refresh()), 'Menu updated successfully');
    }

    public function destroy(Menu $menu)
    {
        $this->menuService->handleDelete($menu);
        $this->menu->delete($menu->id);
        return ResponseHelper::success(null, 'Menu deleted successfully');
    }
}
