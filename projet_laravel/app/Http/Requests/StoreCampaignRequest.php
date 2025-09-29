<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
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
            'category' => 'required|in:reforestation,nettoyage,sensibilisation,recyclage,biodiversite,energie_renouvelable,autre',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'goal' => 'nullable|numeric|min:0',
            'environmental_impact' => 'nullable|string',
            'image_url' => 'nullable|url',
            'visibility' => 'boolean',
            'tags' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,completed,cancelled'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom de la campagne est obligatoire',
            'category.required' => 'La catégorie est obligatoire',
            'start_date.required' => 'La date de début est obligatoire',
            'end_date.after' => 'La date de fin doit être après la date de début',
        ];
    }
}