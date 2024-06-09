<?php

// routes/api.php

use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::get('/home-data', function () {
    $openTickets = Ticket::where('status', 'open')->count();
    $closedTickets = Ticket::where('status', 'closed')->count();

    return response()->json([
        'openTickets' => $openTickets,
        'closedTickets' => $closedTickets,
    ]);
});