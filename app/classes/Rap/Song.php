<?php

namespace App\Rap;

class Song extends \App\Rap\Abstracts\Song {

    public function canRap(\Core\User\User $user) {

        /** @var \App\Rap\Song */
        $last_line_index = $this->getCount($this->loadAll()) - 1;
        $last_line = $this->loadAll()[$last_line_index];

        if ($last_line->getEmail() != $user->getEmail()) {
            return true;
        }
    }

}
