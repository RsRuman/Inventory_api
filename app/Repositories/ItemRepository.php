<?php

namespace App\Repositories;

use App\Interfaces\ItemRepositoryInterface;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ItemRepository implements ItemRepositoryInterface
{
    /**
     * @param Model|Builder $inventory
     * @param int $perPage
     * @return mixed
     */
    public function getALlItems(Model|Builder $inventory, int $perPage): mixed
    {
        return $inventory->items()
            ->paginate($perPage);
    }

    /**
     * @param Model|Builder $inventory
     * @param int $id
     * @return mixed
     */
    public function getItemById(Model|Builder $inventory, int $id): mixed
    {
        return $inventory->items()
            ->where('id', $id)
            ->with('inventory')
            ->find($id);
    }

    /**
     * @param Model|Builder $inventory
     * @param array $data
     * @return mixed
     */
    public function createItem(Model|Builder $inventory, array $data): mixed
    {
        return $inventory->items()
            ->create($data);
    }

    /**
     * @param Model|Builder $item
     * @param array $data
     * @return bool|int
     */
    public function updateItem(Model|Builder $item, array $data): bool|int
    {
        return $item->update($data);
    }

    /**
     * @param Builder|Model $item
     * @return bool|mixed|null
     */
    public function deleteItem(Model|Builder $item): mixed
    {
        return $item->delete();
    }
}
