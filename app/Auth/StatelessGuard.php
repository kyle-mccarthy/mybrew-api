<?php namespace App\Auth;

use App\User;
use Auth;
use Validator;
use Hash;

class StatelessGuard
{
    /**
     * Attempt to get the user by the email and hashed password passed through the arguments.  If the attempt is not
     * successful return false.  If the attempt is successful set the user on the auth, set the API token, save the user,
     * and then return true.
     *
     * @param $email
     * @param $password
     * @return bool
     */
    public function attempt($email, $password)
    {
        // get the user
        $user = User::where('email', '=', $email)->first();

        // if the credentials were invalid the user record will be null
        if (is_null($user) || !Hash::check($password, $user->password)) {
            dd($password);
            return false;
        }

        $this->generateUniqueToken($user);

        Auth::login($user);
        return true;
    }

    /**
     * Attempt to set the current user using the api auth token.  Will check for a user in the DB with the token
     * assigned to their account.
     *
     * @param $token
     * @return bool
     */
    public function authByToken($token)
    {
        $user = User::where('api_token', '=', $token)->first();
        if (is_null($user)) {
            return false;
        }
        Auth::login($user);
        return true;
    }

    /**
     * Generate a unique token for the user
     *
     * @param $user
     * @return null|string
     */
    public function generateUniqueToken($user)
    {
        if (!is_null($user)) {
            // generate a unique token for the user, really important to ensure that the token is actually unique,
            // if it is not there will be a DB constraint violation for non-unique, but also would mess up the ability
            // to quickly auth a user by the token alone
            $token = null;
            while (true) {
                $token = str_random(60);
                $validator = Validator::make(['api_token' => $token], [
                    'api_token' => 'unique:users',
                ]);
                if ($validator->passes()) {
                    break;
                }
            }

            // update the user
            $user->api_token = $token;
            $user->save();
            return $token;
        }
        return null;
    }

}