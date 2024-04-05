<?php

namespace App\Http\Controllers\Api\V1\Inventory;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\CreateInventoryRequest;
use App\Http\Requests\Inventory\UpdateInventoryRequest;
use App\Http\Resources\InventoryResource;
use App\Interfaces\InventoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;

#[AllowDynamicProperties]
class InventoryController extends Controller
{
    public function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * Retrieves inventories
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 20);

        $inventories = $this->inventoryRepository->getALlInventories($perPage);
        $inventories = InventoryResource::collection($inventories);
        $inventories = $this->collectionResponse($inventories);

        return Response::json([
            'message' => 'Inventories retrieved successfully.',
            'data'    => $inventories
        ], HttpResponse::HTTP_OK);

    }

    /**
     * Retrieve an inventory
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $inventory = $this->inventoryRepository->getInventoryById($id);

        if (!$inventory) {
            return Response::json([
                'message' => 'Not found.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([
            'message' => 'Inventory retrieved successfully.',
            'data'    => new InventoryResource($inventory)
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Create an inventory
     * @param CreateInventoryRequest $request
     * @return JsonResponse
     */
    public function create(CreateInventoryRequest $request): JsonResponse
    {
        $data            = $request->safe()->only('name', 'description');
        $data['user_id'] = $request->user('api')->id;

        $inventory = $this->inventoryRepository->createInventory($data);

        if (!$inventory) {
            return Response::json([
                'message' => 'Could not create inventory. Please try later.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Inventory created successfully.'
        ], HttpResponse::HTTP_CREATED);
    }

    /**
     * Update an inventory
     * @param UpdateInventoryRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateInventoryRequest $request, $id): JsonResponse
    {
        $inventory = $this->inventoryRepository->getInventoryById($id);

        if (!$inventory) {
            return Response::json([
                'message' => 'Not found.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $data = $request->safe()->only('name', 'description');

        $inventoryUpdate = $this->inventoryRepository->updateInventory($inventory, $data);

        if (!$inventoryUpdate) {
            return Response::json([
                'message' => ' Could not update inventory. Please try later.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Inventory updated successfully.'
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Delete an inventory
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $inventory = $this->inventoryRepository->getInventoryById($id);

        if (!$inventory) {
            return Response::json([
                'message' => 'Not found.'
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $inventoryDelete = $this->inventoryRepository->deleteInventory($inventory);

        if (!$inventoryDelete) {
            return Response::json([
                'message' => 'Could not delete. Please try later.'
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Inventory deleted successfully.'
        ], HttpResponse::HTTP_OK);
    }
}
