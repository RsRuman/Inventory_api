<?php

namespace App\Http\Controllers\Api\V1\Item;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Item\CreateItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Interfaces\InventoryRepositoryInterface;
use App\Interfaces\ItemRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;

#[AllowDynamicProperties]
class ItemController extends Controller
{
    public function __construct(ItemRepositoryInterface $itemRepository, InventoryRepositoryInterface $inventoryRepository)
    {
        $this->itemRepository      = $itemRepository;
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * Retrieve items
     * @param Request $request
     * @param $invId
     * @return JsonResponse
     */
    public function index(Request $request, $invId): JsonResponse
    {
        $inventory = $this->inventoryRepository->getInventoryById($invId);

        if (!$inventory) {
            return Response::json([
                'message' => 'Invalid inventory.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $perPage = $request->input('per_page', 20);

        $items = $this->itemRepository->getALlItems($inventory, $perPage);
        $items = ItemResource::collection($items);
        $items = $this->collectionResponse($items);

        return Response::json([
            'message' => 'Items retrieved successfully.',
            'data'    => $items
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Retrieve an item
     * @param $invId
     * @param $id
     * @return JsonResponse
     */
    public function show($invId, $id): JsonResponse
    {
        $inventory = $this->inventoryRepository->getInventoryById($invId);

        if (!$inventory) {
            return Response::json([
                'message' => 'Invalid inventory.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $item = $this->itemRepository->getItemById($inventory, $id);

        if (!$item) {
            return Response::json([
                'message' => 'Not found.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([
            'message' => 'Item retrieved successfully.',
            'data'    => new ItemResource($item)
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Create an item
     * @param CreateItemRequest $request
     * @param $invId
     * @return JsonResponse
     */
    public function create(CreateItemRequest $request, $invId): JsonResponse
    {
        $inventory = $this->inventoryRepository->getInventoryById($invId);

        if (!$inventory) {
            return Response::json([
                'message' => 'Invalid inventory.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $data = $request->safe()->only('name', 'description', 'quantity');

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = uploadImage($request->file('photo'), 'photos');
        }

        $data['photo'] = $photoPath;

        $item = $this->itemRepository->createItem($inventory, $data);

        if (!$item) {
            return Response::json([
                'message' => 'Could not create item. Please try later.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Item created successfully.'
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Update an item
     * @param UpdateItemRequest $request
     * @param $invId
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateItemRequest $request, $invId, $id): JsonResponse
    {
        $inventory = $this->inventoryRepository->getInventoryById($invId);

        if (!$inventory) {
            return Response::json([
                'message' => 'Invalid inventory.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $item = $this->itemRepository->getItemById($inventory, $id);

        if (!$item) {
            return Response::json([
                'message' => 'Invalid item.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $data = $request->safe()->only('inventory_id', 'name', 'description', 'quantity');

        $photoPath = $item->photo;

        if ($request->hasFile('photo')) {
            $photoPath = uploadImage($request->file('photo'), 'photos');
        }

        $data['photo'] = $photoPath;

        $itemUpdate = $this->itemRepository->updateItem($item, $data);

        if (!$itemUpdate) {
            return Response::json([
                'message' => 'Could not update item. Please try later.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Item updated successfully.'
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Delete an item
     * @param $invId
     * @param $id
     * @return JsonResponse
     */
    public function destroy($invId, $id): JsonResponse
    {
        $inventory = $this->inventoryRepository->getInventoryById($invId);

        if (!$inventory) {
            return Response::json([
                'message' => 'Invalid inventory.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $item = $this->itemRepository->getItemById($inventory, $id);

        if (!$item) {
            return Response::json([
                'message' => 'Invalid item.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $itemDelete = $this->itemRepository->deleteItem($inventory, $item);

        if (!$itemDelete) {
            return Response::json([
                'message' => 'Could not delete an item. Please try later.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([
            'message' => 'Item deleted successfully.'
        ], HttpResponse::HTTP_OK);
    }
}
