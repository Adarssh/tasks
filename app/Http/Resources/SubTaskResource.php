<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Adds id to resource url if requesting all or create
        $extra = (last($request->segments()) != $this->id
            && in_array($request->method(), ["GET", "POST"])
            ? "/{$this->id}"
            : ""
        );

        return [
            "data" => [
                "id" => $this->id,
                "title" => $this->title,
                "due_date" => $this->due_date,
                "status" => $this->status,
                "created_at" => $this->created_at,
                "task" => $this->Task,
            ],
            "links" => [
                "self" => $request->fullUrl() . $extra,
            ]
        ];
    }
}
