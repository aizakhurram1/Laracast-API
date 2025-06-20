<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Policies\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketsController extends ApiController
{
    protected $policy_class = TicketPolicy::class;

    //which user has which tickets
    public function index($author_id, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filter($filters)->paginate());
    }

    public function store(StoreTicketRequest $request, $author_id)
    {

         try {
            
            $this->isAble('store', Ticket::class);
             return new TicketResource(Ticket::create($request->mappedAttributes([
                'author' => 'user_id'
             
            ])));
        // TO DO ADD TICKET
        
        } catch (AuthorizationException $exception) {

            return $this->error('You are not authorized to create ticket', 401);
        } 

    }
    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        try {
              $ticket = Ticket::findOrFail($ticket_id);

                if ((int) $ticket->user_id !== (int) $author_id) {
                    return $this->error('This ticket does not belong to the specified author.',403 );
                }
            $this->isAble('replace', $ticket);

            if ($ticket->user_id == $author_id) {

                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }

        } catch (ModelNotFoundException $exception) {
                return $this->error('Ticket not found', 404);
        } catch (AuthorizationException $exception) {

            return $this->error('You are not authorized to update ticket', 401);
        } 

    }
    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        // PATCH
        try {
                $ticket = Ticket::findOrFail($ticket_id);

                if ((int) $ticket->user_id !== (int) $author_id) {
                    return $this->error('This ticket does not belong to the specified author.',403 );
                }
            

                $this->isAble('update', $ticket);
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            

        } catch (ModelNotFoundException $exception) {
                return $this->error('Ticket not found', 404);
        } catch (AuthorizationException $exception) {

                return $this->error('You are not authorized to update ticket', 401);
        } 


    }
    public function destroy($author_id, $ticket_id)
    {
       
        //to ensure no server error is shown to prevent from vulnerability as reponse is a part of payload
        try {
            
               $ticket = Ticket::findOrFail($ticket_id);

                if ((int) $ticket->user_id !== (int) $author_id) {
                    return $this->error('This ticket does not belong to the specified author.',403 );
                }
                            
                $this->isAble('delete', $ticket);
                $ticket->delete();

                return $this->ok('Ticket successfully deleted', null);
            

        } catch (ModelNotFoundException $exception) {

            return $this->error('Ticket not found', 404);


        }
    }

}
