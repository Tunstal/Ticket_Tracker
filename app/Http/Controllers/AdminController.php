<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Support\Facades\Request;

class AdminController
{
    public function adminTickets()
    {
        return Inertia::render('ManageTickets', [
            'categories' => Category::all()
                ->when(Request::input('search'), function($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                }),
            'tickets' => Ticket::select('tickets.*')
                ->when(Request::input('search'), function($query, $search) {
                    $query->where(function($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
                })
                ->when(Request::input('category'), function($query, $category) {
                    $query->where('idCategory', $category);
                })
                ->orderByDesc('updated_at')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($ticket) => [
                    'idTicket' => $ticket->idTicket,
                    'title' => $ticket->title,
                    'status' => $ticket->status,
                    'idCategory' => $ticket->idCategory,
                    'created_at' => $ticket->created_at,
                    'updated_at' => $ticket->updated_at,
                ]),
            'filters' => Request::only(['search', 'category']),
        ]);
    }

    public function ticketStatus($idTicket)
    {

        $ticket = Ticket::find($idTicket);
        if ($ticket->status == 'open') {
            $ticket->status = 'closed';
        } else {
            $ticket->status = 'open';
        }
        $ticket->save();

        return redirect("/tickets/{$idTicket}");
    }

    function deleteTicket($idTicket)
    {
        $ticket = Ticket::find($idTicket);

        foreach ($ticket->comments as $comment) {
            $comment->delete();
        }

        $ticket->delete();

        return redirect('/admin/tickets');
    }

    public function adminSettings()
    {
        return Inertia::render('AdminSettings', [
            'users' => User::query()
                ->when(Request::input('search'), function($query, $search) {
                    $query->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('idUser', 'like', "%{$search}%");
                })
                ->where('role', 'user')
                ->orderBy('username')
                ->paginate(10)
                ->withQueryString()
                ->through(fn($user) => [
                    'idUser' => $user->idUser,
                    'username' => $user->username,
                    'email' => $user->email,
                ]),
            'filters' => Request::only(['search']),
        ]);
    }

    public function deleteUser($idUser)
    {
        $user = User::find($idUser);

        $tickets = $user->tickets;

        if($tickets) {
            foreach ($tickets as $ticket) {
                foreach ($ticket->comments as $comment) {
                    $comment->delete();
                }
                $ticket->delete();
            }
        }

        $user->delete();

        return redirect('/admin/settings');
    }
}
