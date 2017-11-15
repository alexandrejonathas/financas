<?php
/**
 * Created by PhpStorm.
 * User: jlima
 * Date: 05/11/2017
 * Time: 21:06
 */

namespace MMoney\View\Twig;


use MMoney\Auth\AuthInterface;

class TwigGlobals extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var AuthInterface
     */
    private $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function getGlobals()
    {
        return [
            "Auth" => $this->auth
        ];
    }

}