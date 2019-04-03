<?php

namespace SaschaEnde\Users\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

class BannedHostsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    public function checkIfBanned($email) {
        $emailparts = explode('@', $email);

        /** @var QueryResult $results */
        $results = $this->findByHost($emailparts[1]);

        if ($results->count() >= 1) {
            return true;
        } else {
            return false;
        }
    }

}
