<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;

class TicketPolicy
{
    public function store(User $user)
    {
        if ($user->tokenCan(Abilities::CreateTicket)
            || $user->tokenCan(Abilities::CreateOwnTicket)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Ticket $ticket)
    {

        if ($user->tokenCan(Abilities::UpdateTicket)) {
            return true;
        } elseif ($user->tokenCan(Abilities::UpdateOwnTicket)) {
            return $user->id === $ticket->user_id;

        }

        return false;
    }

    public function delete(User $user, Ticket $ticket)
    {

        if ($user->tokenCan(Abilities::DeleteTicket)) {
            return true;
        } elseif ($user->tokenCan(Abilities::DeleteOwnTicket)) {
            return $user->id === $ticket->user_id;

        }

        return false;
    }

    public function replace(User $user, Ticket $ticket)
    {

        return $user->tokenCan(Abilities::ReplaceTicket);

    }
}
