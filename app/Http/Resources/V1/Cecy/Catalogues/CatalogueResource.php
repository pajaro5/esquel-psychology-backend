<?php

namespace App\Http\Resources\V1\Cecy\Catalogues;

use Illuminate\Http\Resources\Json\JsonResource;

class CatalogueResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'additional_information_id' => $this->additional_information_id,
            'person_type_id' => $this->person_type_id,
            'user_id' => $this->user_id,
        ];
    }
}