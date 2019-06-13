<?php

namespace SaschaEnde\Users\Domain\Repository;

use t3h\t3h;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class PagesRepository extends Repository {

    private $pageIds = [];
    private $sorting = [];
    private $offset = 0;
    private $limit = null;

    public function reset(){
        $this->pageIds = [];
        $this->sorting = [];
        $this->offset = 0;
        $this->limit = null;
    }

    public function setPage($pid){
        if(!is_array($pid)){
            $pid = explode(',',$pid);
        }
        $this->pageIds = array_merge($this->pageIds,$pid);
    }

    public function setSorting($field, $order = 'asc'){
        if ($order == 'ASC') {
            $ordering = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING;
        } else {
            $ordering = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
        }

        $this->sorting = [
            $field => $ordering
        ];
    }

    public function setOffset($offset){
        $this->offset = intval($offset);
    }

    public function setLimit($limit){
        $this->limit = intval($limit);
    }

    public function getResults(){
        $query = $this->createQuery();
        $query->setQuerySettings(t3h::Database()->getQuerySettings(false));

        $constraints = [];

        // Auf SeitenIDs eingrenzen
        if (count($this->pageIds) > 0) {
            $constraints[] = $query->in('uid', $this->pageIds);
        }

        // Sortierung
        $query->setOrderings($this->sorting);

        // Offset
        if($this->offset >= 1){
            $query->setOffset($this->offset);
        }

        // Limit
        if($this->limit >= 1){
            $query->setLimit($this->limit);
        }

        if(count($constraints) >= 1){
            $query->matching($query->logicalAnd($constraints));
        }

        // Rückgabe
        return $query->execute();
    }

}