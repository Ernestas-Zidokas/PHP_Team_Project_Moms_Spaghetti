<?php

namespace App\Rap\Abstracts;

abstract class Song extends \App\Rap\Model\ModelLine {

    abstract function canRap(\Core\User\User $user);
}
