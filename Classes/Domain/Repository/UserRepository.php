<?php

namespace SaschaEnde\Users\Domain\Repository;


use SaschaEnde\Users\Domain\Model\BannedHosts;
use SaschaEnde\Users\Domain\Model\User;
use t3h\t3h;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
        $constraints[] = $query->greaterThanOrEqual("crdate", time()-86400);
        $query->matching($query->logicalAnd($constraints));
        return $query->execute()->count();
    }

    /**
     * @param string $date
     * @return int
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function getRegisteredByDate($date = '-1days'){
        $dt = new \DateTime($date);
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->greaterThanOrEqual("crdate", mktime(0,0,1,$dt->format('m'),$dt->format('d'),$dt->format('Y')));
        $constraints[] = $query->lessThanOrEqual("crdate", mktime(23,59,59,$dt->format('m'),$dt->format('d'),$dt->format('Y')));
        $query->matching($query->logicalAnd($constraints));
        $count = $query->execute()->count();

        return [
            'date' => $dt->format('Y-m-d'),
            'users' => $count
        ];
    }

    /**
     * Check users for spam hosts
     * @param QueryResult $hosts
     * @return int
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function checkHosts(QueryResult $hosts){
        $query = $this->createQuery();
        $query->setQuerySettings(t3h::Database()->getQuerySettings(false));
        $constraints = [];

        foreach ($hosts as $host){
            /** @var BannedHosts $host */
            $constraints[] = $query->like("email", '%@'.$host->getHost());
        }

        $query->matching($query->logicalOr($constraints));

        return $query->execute();
    }

}
