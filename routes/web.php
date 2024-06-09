<?php

use App\Http\Controllers\AccountController;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;

// Routes only accessible to authenticated users with the role of admin
Route::middleware(CheckRole::class . ':admin')->group(function () {
    
    //Manage Tickets page
    Route::get('/admin/tickets', [AdminController::class, 'adminTickets'])->name('admin.tickets');

    //Close Ticket
    Route::put('/admin/tickets/{idTicket}', [AdminController::class, 'ticketStatus'])->name('admin.closeTicket');

    //Delete Ticket
    Route::delete('/admin/tickets/{idTicket}', [AdminController::class, 'deleteTicket'])->name('admin.deleteTicket');

    //Admin Settings Page
    Route::get('/admin/settings', [AdminController::class, 'adminSettings'])->name('admin.settings');

    //Delete User
    Route::delete('/admin/settings/{idUser}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});

// Routes only accessible to authenticated users
Route::middleware('auth')->group(function () {

    //Logout route
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    //Tickets page
    Route::get('/tickets', [TicketController::class, 'create'])->name('tickets');

    //Create ticket page
    Route::get('/tickets/create', function () {
        return Inertia::render('CreateTicket', [
            'categories' => Category::all()
        ]);
    })->name('tickets.create');
    Route::post('/tickets/create', [TicketController::class, 'store'])->name('tickets.store');

    //View ticket page
    Route::get('/tickets/{ticket}', [TicketController::class, 'showTicket'])->name('ticket.show');

    //Create Comment
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comment.store');

    //Edit Comment
    Route::put('/tickets/{ticket}/comments/{comment}', [CommentController::class, 'edit'])->name('comment.edit');

    //Account Settings Page
    Route::get('/account', [AccountController::class, 'showUserSettings'])->name('account');

    //Update Password
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.updatePassword');

    //Update Username
    Route::put('/account/username', [AccountController::class, 'updateUsername'])->name('account.updateUsername');
});

// Home page
Route::get('/', function () {
    if (auth()->check() && auth()->user()->role == 'user') {
        $openTickets = Ticket::where('status', 'open')->where('idUser', auth()->user()->idUser)->count();
        $closedTickets = Ticket::where('status', 'closed')->where('idUser', auth()->user()->idUser)->count();
    
        return Inertia::render('Home', [
            'openTickets' => $openTickets,
            'closedTickets' => $closedTickets,
        ]);
    }
    elseif (auth()->check() && auth()->user()->role == 'admin') {
        $openTickets = Ticket::where('status', 'open')->count();
        $closedTickets = Ticket::where('status', 'closed')->count();

        return Inertia::render('Home', [
            'openTickets' => $openTickets,
            'closedTickets' => $closedTickets,
        ]);
    }

    return Inertia::render('Home');
})->name('home');

// Login page
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store');

// Register page
Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');

// Register user
Route::post('/register', function (Request $request) {
    $attributes = $request->validate([
        'username' => ['required', 'max:255', 'unique:users,username'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'min:8', 'max:255'],
    ]);

    $attributes['username'] = strip_tags($attributes['username']);
    $attributes['email'] = strip_tags($attributes['email']);
    $attributes['password'] = strip_tags($attributes['password']);

    $attributes['password'] = bcrypt($attributes['password']);

    User::create($attributes);

    return redirect()->route('login');
})->name('register.post');