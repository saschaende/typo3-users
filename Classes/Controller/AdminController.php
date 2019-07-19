<?php

namespace SaschaEnde\Users\Controller;

use SaschaEnde\Users\Domain\Model\BannedHosts;
use SaschaEnde\Users\Domain\Repository\BannedHostsRepository;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use t3h\t3h;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class AdminController extends ActionController {

    /**
     * @var BannedHostsRepository
     */
    protected $bannedHostsRepository;

    /**
     * @var UserRepository
     */
    protected $frontendUserRepository;

    public function initializeAction() {
        // Banned Hosts Repo
        $this->bannedHostsRepository = $this->objectManager->get(BannedHostsRepository::class);
        $this->bannedHostsRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());
        // User Repo
        $this->frontendUserRepository = $this->objectManager->get(UserRepository::class);
        $this->frontendUserRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());
    }

    public function listAction() {

    }

    public function importbanlistAction() {
        // $filePath = PATH_typo3conf . 'ext/users/Resources/Private/Data/spamhosts.txt'; // use local file
        $fileData = file('https://raw.githubusercontent.com/saschaende/typo3-users/master/Resources/Private/Data/spamhosts.txt');
        $stats = [
            'added' => 0,
            'exist' => 0
        ];
        foreach ($fileData as $hostname) {
            $hostname = strtolower(trim($hostname));
            $host = $this->bannedHostsRepository->findOneByHost($hostname);
            if (!$host) {
                $host = new BannedHosts();
                $host->setHost($hostname);
                $this->bannedHostsRepository->add($host);
                $stats['added']++;
            } else {
                $stats['exist']++;
            }
        }
        $this->view->assignMultiple([
            'stats' => $stats
        ]);
    }

    public function checkAction() {
        $hosts = $this->bannedHostsRepository->findAll();
        if ($hosts->count() >= 1) {
            $users = $this->frontendUserRepository->checkHosts($hosts);
        } else {
            $users = [];
        }


        $this->view->assignMultiple([
            'users' => $users
        ]);


    }


}