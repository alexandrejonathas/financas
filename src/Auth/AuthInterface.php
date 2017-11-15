<?php
/**
 * Created by PhpStorm.
 * User: jlima
 * Date: 05/11/2017
 * Time: 18:27
 */

declare(strict_types=1);

namespace MMoney\Auth;


use MMoney\Model\UserInterface;

interface AuthInterface
{
    function login(array $credentials): bool;
    function check(): bool;
    function logout(): void;
    function hashPassword(string $password): string;
    function user(): ?UserInterface;
}