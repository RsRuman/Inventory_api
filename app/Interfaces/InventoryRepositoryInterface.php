<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface InventoryRepositoryInterface
{
    public function getALlInventories(int $perPage);

    public function getInventoryById(int $id);

    public function createInventory(array $data);

    public function updateInventory(Model|Builder $inventory, array $data);

    public function deleteInventory(Model|Builder $inventory);
}
