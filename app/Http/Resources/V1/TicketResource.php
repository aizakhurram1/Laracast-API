<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'type' => 'ticket',
          'id' => (string) $this->ticket_id,
          'attributes' => [
          'title' => $this->title,
          'description' => $this->when(
            $request->routeIs('tickets.show'),
            $this->description,
          ),
          'status' => $this->status,
          'createdAt' => $this->created_at,
          'updatedAt' => $this->updated_at,
    ],
    'relationships'=>[
         'author' => [
            'data' => [
                'type' => 'user',
                'id' => $this->user_id,
            ],

            'links' => [
                'self' => route('authors.show', ['author' => $this->user_id]),
            ]
         ]
    ],
    'includes' => [
            // The author key is only included if the user relationship was loaded.

            'author' => $this->whenLoaded('author', function () {
                return new UserResource($this->user);
            }),
        ],
    
    'links' => [
       
        'self' => route('tickets.show', $this->ticket_id),
    ],
];

}
}
