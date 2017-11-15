<?php
/**
 * Created by PhpStorm.
 * User: jlima
 * Date: 05/11/2017
 * Time: 18:31
 */

namespace MMoney\Auth;


use MMoney\Model\UserInterface;

class Auth implements AuthInterface
{

    /**
     * @var JasnyAuth
     */
    private $jasnyAuth;

    public function __construct(JasnyAuth $jasnyAuth)
    {
        $this->jasnyAuth = $jasnyAuth;
        $this->sessionStart();
    }

    function login(array $credentials): bool
    {
        list("email"=>$email, "password"=>$password) = $credentials;
        return $this->jasnyAuth->login($email, $password) !== null;
    }

    function check(): bool
    {
        return $this->user() !== null;
    }

    function logout(): void
    {
        $this->jasnyAuth->logout();
    }

    function user(): ?UserInterface
    {
        return $this->jasnyAuth->user();
    }

    public function hashPassword(string $password): string
    {
        return $this->jasnyAuth->hashPassword($password);
    }

    protected function sessionStart()
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
    }

}