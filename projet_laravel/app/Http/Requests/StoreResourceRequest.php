<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity_needed' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'provider' => 'nullable|string|max:255',
            'resource_type' => 'required|in:money,food,clothing,medical,equipment,human,other',
            'category' => 'required|in:materiel,financier,humain,technique',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string',
            'image_url' => 'nullable|url',
            'campaign_id' => 'required|exists:campaigns,id'
        ];
    }
}