<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "brand_id" => $this->id, //from table column name
            "brand_name" => $this->name,
            //now application's location(domain)
            "brand_photo" => url($this->photo),
        ];
    }
}
