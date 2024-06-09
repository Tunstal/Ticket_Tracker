<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'idTicket';

    protected $fillable = [
        'title',
        'description',
        'status',
        'idUser',
        'idCategory',
        'created_at',
        'updated_at',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'idTicket');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'idCategory');
    }

    public function getLastUpdatedAttribute()
    {
        $commentsMaxUpdatedAt = $this->comments()->max('updated_at');
    
        if ($commentsMaxUpdatedAt) {
            return max($this->updated_at, $commentsMaxUpdatedAt);
        }
    
        return $this->updated_at;
    }
}
