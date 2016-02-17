<?php

class AuthTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->json('POST', '/api/v1/auth/register', ['email' => 'test@test.com', 'password' => 'testing_password'])
            ->seeJson(['token']);
    }

}