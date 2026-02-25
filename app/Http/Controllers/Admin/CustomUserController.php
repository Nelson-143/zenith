<?php

namespace app\Http\Controllers\Admin;

use app\Http\Controllers\Controller;
use app\Models\User;
use Illuminate\Http\Request;

class CustomUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        
        return view('admin.users.index', [
            'users' => $users,
            'title' => 'User Management'
        ]);
    }
    
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.show', [
            'user' => $user,
            'title' => 'User Details'
        ]);
    }
}