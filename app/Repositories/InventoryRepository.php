<?php

namespace App\Repositories;

use App\Interfaces\InventoryRepositoryInterface;
use App\Models\Inventory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InventoryRepository implements InventoryRepositoryInterface
{
    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getALlInventories(int $perPage): LengthAwarePaginator
    {
        return Inventory::query()
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return Model|Builder|array|null
     */
    public function getInventoryById(int $id): Model|Builder|array|null
    {
        return Inventory::query()
            ->with('user')
            ->find($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createInventory(array $data): mixed
    {
        return Inventory::create($data);
    }

    /**
     * @param Model|Builder $inventory
     * @param array $data
     * @return bool|int
     */
    public function updateInventory(Model|Builder $inventory, array $data): bool|int
    {
        return $inventory->update($data);
    }

    /**
     * @param Model|Builder $inventory
     * @return mixed
     */
    public function deleteInventory(Model|Builder $inventory): mixed
    {
        return $inventory->delete();
    }
}
