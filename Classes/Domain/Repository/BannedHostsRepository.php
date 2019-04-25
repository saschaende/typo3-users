<?php

namespace SaschaEnde\Users\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

class BannedHostsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    public function checkIfBanned($email) {
        $emailparts = explode('@', $email);

        /** @var QueryResult $results */
        $results = $this->findByHost($emailparts[1]);

        // Get the main domain
        $domainparts = explode('.',$emailparts[1]);


        $hostname = $domainparts[count($domainparts)-2].'.'.$domainparts[count($domainparts)-1];

        /** @var QueryResult $results2 */
        $results2 = $this->findByHost($hostname);

        if ($results->count() >= 1 || $results2->count() >= 1) {
            return true;
        } else {
            return false;
        }
    }

}
