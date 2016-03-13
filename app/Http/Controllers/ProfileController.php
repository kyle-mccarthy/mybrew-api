<?php namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function show()
    {
        $user = $this->auth->user();
        return response([
            'status' => 'ok',
            'message' => 'Information about the currently authenticated user.',
            'user' => $user,
        ]);
    }
}