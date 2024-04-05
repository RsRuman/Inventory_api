<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface ItemRepositoryInterface
{
    public function getALlItems(Model|Builder $inventory, int $perPage);

    public function getItemById(Model|Builder $inventory,  int $id);

    public function createItem(Model|Builder $inventory, array $data);

    public function updateItem(Model|Builder $item, array $data);

    public function deleteItem(Model|Builder $item);
}
