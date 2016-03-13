<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Carbon\Carbon;

class ApiAuthController extends Controller
{

    /**
     * Attempt to log a user in based on the email and password combination POSTed.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // could not validate
        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid data.',
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        // authentication not successful
        if (!$this->auth->attempt($request->get('email'), $request->get('password'))) {
            return response([
                'status' => 'failed',
                'message' => 'Could not authenticate user based on email and password combination.'
            ], 401);
        }

        // authentication successful, return the token
        return response([
            'status' => 'ok',
            'message' => 'The user has been authenticated.',
            'token_type' => 'bearer',
            'token' => $this->auth->user()->api_token,
        ]);
    }

    /**
     * Attempt to create a new user based on the information POSTed.  If the attempt was successful, respond with
     * the success message and the token to be used with the user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request)
    {
        $dt = new Carbon();
        $before = $dt->subYears(21)->format('Y-m-d');

        $messages = [
            'birthday.before' => 'You must be at least 21 years old to use the application'
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'birthday' => 'required|date|before:' . $before
        ], $messages);

        // could not validate
        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid data.',
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        $user = new User;
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->save();

        $token = $this->auth->generateUniqueToken($user);

        return response([
            'status' => 'ok',
            'message' => 'The user has been created.',
            'token_type' => 'bearer',
            'token' => $token,
        ]);
    }
}