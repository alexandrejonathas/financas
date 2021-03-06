<?php
/**
 * Created by PhpStorm.
 * User: jlima
 * Date: 05/11/2017
 * Time: 18:43
 */

namespace MMoney\Auth;


use Jasny\Auth\Sessions;
use Jasny\Auth\User;
use MMoney\Repository\RepositoryInterface;

class JasnyAuth extends \Jasny\Auth
{

    use Sessions;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fetch a user by ID
     *
     * @param int|string $id
     * @return User|null
     */
    public function fetchUserById($id)
    {
        return $this->repository->find($id, false);
    }

    /**
     * Fetch a user by username
     *
     * @param string $username
     * @return User|null
     */
    public function fetchUserByUsername($username)
    {
        return $this->repository->findByField("email", $username)[0];
    }
}