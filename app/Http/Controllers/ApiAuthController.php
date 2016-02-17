<?php use App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Auth\StatelessGuard;
use App\User;

class ApiAuthController extends Controller
{
    public function __construct()
    {
        $this->auth = new StatelessGuard();
    }

    /**
     * Attempt to log a user in based on the email and password combination POSTed.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // authentication not successful
        if (!$this->auth->attempt($request->get('email'), bcrypt($request->get('password')))) {
            return response([
                'message' => 'Could not authenticate user based on email and password combination.'
            ], 400);
        }

        // authentication successful, return the token
        return response([
            'message' => 'The user has been authenticated.',
            'token_type' => 'bearer',
            'token' => Auth::user()->token,
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
        $this->validate($request, [
            'email' => 'required|unique:users',
            'password' => 'required|min:8'
        ]);

        $user = User::create($request->all());
        $token = $this->auth->generateUniqueToken($user);

        return response([
            'message' => 'The user has been created.',
            'token_type' => 'bearer',
            'token' => $token,
        ]);
    }
}