<?php
/**
 * Created by PhpStorm.
 * User: jlima
 * Date: 05/11/2017
 * Time: 21:26
 */

namespace MMoney\Model;


interface UserInterface
{
    function getId():int;
    function getFullName():string;
    function getEmail():string;
    function getPassword():string;
}