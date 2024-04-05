<?php

namespace App\Http\Requests\Item;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $inventoryId = request()->invId;

        return [
            'name'        => 'required|string|max:55|unique:items,name,NULL,id,inventory_id,' . $inventoryId,
            'description' => 'required|string|max:255',
            'photo'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity'    => 'required|integer|between:0,100000'
        ];
    }
}
