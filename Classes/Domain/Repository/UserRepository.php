<?php

namespace SaschaEnde\Users\Domain\Repository;


use t3h\t3h;

class UserRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    /**
     * Get number of registered users for a website in the last 24h
     * @param $website
     * @return int
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function getRegistered24h($website){
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals("users_website", $website);
        $constraints[] = $query->greaterThanOrEqual("crdate", time()-86400);
        $query->matching($query->logicalAnd($constraints));
        return $query->execute()->count();
    }

}
