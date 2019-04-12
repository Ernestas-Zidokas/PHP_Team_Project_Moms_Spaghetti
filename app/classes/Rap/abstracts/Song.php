<?php

namespace App\Rap\Abstacts;

abstract class Song extends App\Model\ModelLine {

    const RAP_SUCCESS = 1;
    const RAP_FAIL = -1;

    abstract function canRap(\Core\User\User $user);
}
