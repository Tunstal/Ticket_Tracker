<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController
{
    // Displays the Ticket page with comments
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'content' => ['required', 'string'],
            'idUser' => ['required', 'integer'],
            'idTicket' => ['required', 'integer'],
        ]);

        $attributes['content'] = strip_tags($attributes['content']);

        Comment::create($attributes);

        $ticket = Ticket::find($attributes['idTicket']);
        $ticket->touch();

        return redirect()->route('ticket.show', $ticket);
    }

    // Function to edit a comment
    public function edit(Request $request, $idTicket,  $idComment)
    {
        $ticket = Ticket::find($idTicket);
        $comment = Comment::find($idComment);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
        $attributes = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $attributes['content'] = strip_tags($attributes['content']);

        $comment->update($attributes);
        $comment->touch('edited_at');

        return redirect()->route('ticket.show', $ticket);
    }
}
