<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class TicketController
{
    public function create()
    {
        return Inertia::render('Tickets', [
            'categories' => Category::all()
                ->when(Request::input('search'), function($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                }),
            'tickets' => Ticket::where('tickets.idUser', Auth::id())
                ->join('categories', 'tickets.idCategory', '=', 'categories.idCategory')
                ->select('tickets.*', 'categories.name as categoryName')
                ->when(Request::input('search'), function($query, $search) {
                    $query->where(function($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhere('categories.name', 'like', "%{$search}%");
                    });
                })
                ->orderByDesc('updated_at')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($ticket) => [
                    'idTicket' => $ticket->idTicket,
                    'title' => $ticket->title,
                    'status' => $ticket->status,
                    'idCategory' => $ticket->idCategory,
                    'categoryName' => $ticket->categoryName,
                    'created_at' => $ticket->created_at,
                    'updated_at' => $ticket->updated_at,
                ]),
            'filters' => Request::only(['search']),
        ]);
    }

    public function showTicket($id)
    {
        // This query retrieves the ticket and its comments
        $ticket = Ticket::with('user', 'comments.user')
            ->where('idTicket', $id)
            ->first();
    
        return Inertia::render('ViewTicket', [
            'ticket' => $ticket,
            'category' => Category::find($ticket->idCategory),
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $fields = $request->validate([
            'idUser' => 'required',
            'idCategory' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $fields['title'] = strip_tags($fields['title']);
        $fields['description'] = strip_tags($fields['description']);

        Log::info('idCategory: ' . $fields['idCategory']);

        Ticket::create($fields);

        return redirect('/tickets');
    }
}
