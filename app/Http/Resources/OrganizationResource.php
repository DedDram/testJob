<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'building_id' => $this->building_id,
            'phone_numbers' => PhoneNumberResource::collection($this->whenLoaded('phoneNumbers')),
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
        ];
    }

    /**
     * Return a successful response message.
     *
     * @return JsonResponse
     */
    public static function emptyResponse(): JsonResponse
    {
        return response()->json(['message' => 'No organizations found'], 404);
    }
}
