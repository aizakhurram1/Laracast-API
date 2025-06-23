<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if user is authenticated
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $author_id_attr = 'data.relationships.author.data.id';

        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $author_id_attr => [
                'required',
                'integer',
                'exists:users,id',
                'in:'.$this->user()->id, // <== 👈 this ensures author is the authenticated user
            ],
        ];

        $user = $this->user();

        if ($user->tokenCan(Abilities::CreateOwnTicket)) {
            $rules[$author_id_attr] .= '|size:'.$user->id;
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        if ($this->routeIs('authors.tickets.store')) {
            $this->merge([
                'data.relationships.author.data.id' => $this->route('author'),
            ]);
        }
    }
}
