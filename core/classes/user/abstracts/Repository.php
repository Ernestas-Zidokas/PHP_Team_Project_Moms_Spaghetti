<?php

namespace Core\User\Abstracts;

abstract class Repository extends \Core\Model\ModelUser {

    const REGISTER_SUCCESS = 1;
    const REGISTER_ERR_EXISTS = -1;
    
    /**
     * Patikrinam are user'is su tokiu email'u egzistuoja
     * Jeigu ne, tada įtraukiam jį į duombazę ir
     * returniname REGISTER_SUCCESS
     * Jeigu egzistuoja, returniname REGISTER_ERR_EXISTS
     */
    abstract public function register(\Core\User\User $user);

}