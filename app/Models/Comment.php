<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'idComment';

    protected $fillable = [
        'idComment',
        'idUser',
        'idTicket',
        'content',
        'created_at',
        'updated_at',
        'edited_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'idTicket');
    }
}
