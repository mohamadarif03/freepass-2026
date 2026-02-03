<?php

namespace App\Service;

use App\Enums\UploadDiskEnum;
use App\Http\Requests\MenuRequest;
use App\Http\Requests\MenuUpdateRequest;
use App\Models\Menu;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;

class MenuService
{
    use UploadTrait;

    public function handleStore(MenuRequest $request): array
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        $data['image'] = $request->file('image')->store(UploadDiskEnum::MENU->value, 'public');

        return $data;
    }

    public function handleUpdate(MenuUpdateRequest $request, Menu $menu): array
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $this->remove($menu->image);
            $data['image'] = $request->file('image')->store(UploadDiskEnum::MENU->value, 'public');
        }

        if ($request->has('name') && $request->name !== $menu->name) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . uniqid();
        }

        return $data;
    }

    public function handleDelete(Menu $menu): void
    {
        $this->remove($menu->image);
    }
}
