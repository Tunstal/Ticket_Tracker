<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Support\Facades\Request;

class AdminController
{
    // Displays the admin tickets page
    public function adminTickets()
    {
        return Inertia::render('ManageTickets', [
            // Fetch all categories and tickets with search and category filters
            'categories' => Category::all()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                }),
            'tickets' => Ticket::select('tickets.*')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('title', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
                })
                ->when(Request::input('category'), function ($query, $category) {
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

    // Updates the status of a ticket
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

    // Deletes ticket
    function deleteTicket($idTicket)
    {
        $ticket = Ticket::find($idTicket);

        foreach ($ticket->comments as $comment) {
            $comment->delete();
        }

        $ticket->delete();

        return redirect('/admin/tickets');
    }

    // Displays the admin settings page
    public function adminSettings()
    {
        return Inertia::render('AdminSettings', [
            // Fetch all users with search filter
            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
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

    // Deletes user
    public function deleteUser($idUser)
    {
        $user = User::find($idUser);

        // Delete all tickets and comments associated with the user
        if ($user) {
            if ($user->tickets) {
                foreach ($user->tickets as $ticket) {
                    $ticket->comments()->delete();
                    $ticket->delete();
                }
            }
    
            // Only delete the user if all tickets have been deleted
            if ($user->tickets()->count() == 0) {
                $user->delete();
            }
        }

        return redirect('/admin/settings');
    }
}
