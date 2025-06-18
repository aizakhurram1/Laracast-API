<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
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
        //post tickets request
        try{
        $user = User::findOrFail($request->input('data.relationships.author.data.id'));
        
        } catch(ModelNotFoundException $exception){
             
            //return ok to avoid server error as it will be a aknow vulnerability for the attacker
             return $this->ok('user not found', [
                'error' => 'The provided user id does not exist' 
             ]
            );
        }

        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $request->input('data.relationships.author.data.id')

        ];

        return new TicketResource(Ticket::create($model));

    }

    

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
         // the include method is  to conditionally load relationships:
         if ($this->include('author')) {

              $ticket->load('user');
        }

          return new TicketResource($ticket);
    }

    
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        //to ensure no server error is shown to prevent from vulnerability as reponse is a part of payload
        try{
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();

            return $this->ok('Ticket successfully deleted', null);
        
        }catch(ModelNotFoundException $exception){

            return $this->error('Ticket not found', 404);
        
        
        }
    }
}
