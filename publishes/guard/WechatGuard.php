<?php

namespace App\Auth;

use Illuminate\Auth\TokenGuard as BaseTokenGuard;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;

class WechatGuard extends BaseTokenGuard
{
    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        $token = parent::getTokenForRequest();
        try {
            $token = decrypt($token);
        } catch (\Exception $e) {
            if (class_exists('AuthenticationException')) {
                throw new AuthenticationException();
            }
            throw new HttpException(401, '登录失效', $e, [], 401);
        }

        return $token;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        if ($user = $this->provider->retrieveByCredentials($credentials)) {
            $this->setUser($user);
            return true;
        }
    }
}
