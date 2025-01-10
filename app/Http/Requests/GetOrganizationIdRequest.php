<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetOrganizationIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|integer|exists:organizations,id',
        ];
    }

    public function validationData(): ?array
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
