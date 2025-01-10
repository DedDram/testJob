<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetOrganizationsByBuildingRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'building_id' => 'required|integer|exists:buildings,id',
        ];
    }

    public function validationData(): ?array
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
