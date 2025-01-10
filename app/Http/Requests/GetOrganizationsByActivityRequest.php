<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetOrganizationsByActivityRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'activity_id' => 'required|integer|exists:activities,id',
        ];
    }

    public function validationData(): ?array
    {
        return array_merge($this->route()->parameters(), $this->all());
    }
}
