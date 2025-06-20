<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Ticket;
use App\Policies\TicketPolicy;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
          Ticket::class => TicketPolicy::class,
    ];
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::policy(Ticket::class, \App\Policies\TicketPolicy::class);
    }
}