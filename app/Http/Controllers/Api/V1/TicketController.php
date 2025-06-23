<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    protected $policy_class = TicketPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {

        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        // post tickets request
        try {

            $this->isAble('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes()));
            // TO DO ADD TICKET

        } catch (AuthorizationException $exception) {

            return $this->error('You are not authorized to update ticket', 401);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {
        try {

            $ticket = Ticket::findOrFail($ticket_id);
            // the include method is  to conditionally load relationships:
            if ($this->include('author')) {

                $ticket->load('user');
            }

            return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {

            return $this->error('Ticket not found', 404);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        // PATCH
        try {
            $ticket = Ticket::find($ticket_id);
            if (! $ticket) {
                return response()->json(['message' => 'Ticket not found'], 404);
            }

            \Log::info('Auth check:', [
                'user_id' => $request->user()->id,
                'ticket_user_id' => $ticket->user_id,
                'tokenAbilities' => $request->user()->currentAccessToken()->abilities ?? [],
                'canUpdate' => $request->user()->tokenCan('ticket:update'),
                'canUpdateOwn' => $request->user()->tokenCan('ticket:own:update'),
            ]);

            // policy, ability to authorize users
            $this->isAble('update', $ticket);
            $request->user()->id == $ticket->user_id;
            // dd($request->user()->id, $request->user()->id->tokenCan('ticket:update'), $request->user()->idtokenCan('ticket:own:update'));

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {

            return $this->error('Ticket not found', 404);

        } catch (AuthorizationException $exception) {

            \Log::error('Unauthorized: '.$exception->getMessage());

            return $this->error('You are not authorized to update ticket', 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if (! $ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $this->isAble('replace', $ticket);

        $ticket->update($request->mappedAttributes());

        return new TicketResource($ticket);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        // to ensure no server error is shown to prevent from vulnerability as reponse is a part of payload
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $this->isAble('delete', $ticket);

            $ticket->delete();

            return $this->ok('Ticket successfully deleted', null);

        } catch (ModelNotFoundException $exception) {

            return $this->error('Ticket not found', 404);

        }
    }
}
