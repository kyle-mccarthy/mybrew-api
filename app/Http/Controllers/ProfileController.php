<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the current users information
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        $user = $this->auth->user();
        return response([
            'status' => 'ok',
            'message' => 'Information about the currently authenticated user.',
            'user' => $user,
        ]);
    }

    /**
     * Update the user information
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        $user = $this->auth->user();
        $email = $request->get('email');
        $password = $request->get('password');
        $name = $request->get('name');

        if (!!$email) {
            $user->email = $email;
        }

        if (!!$password) {
            $user->password = bcrypt($password);
        }

        if (!!$name) {
            $user->name = $name;
        }

        $user->save();

        return response([
            'status' => 'ok',
            'message' => 'The user has been updated',
            'user' => $user,
        ]);
    }
}