<?php

namespace SaschaEnde\Users\Command;

use SaschaEnde\Users\Domain\Model\News;
use SaschaEnde\Users\Domain\Repository\CompanyRepository;
use SaschaEnde\Users\Domain\Repository\NewsCategoryRepository;
use SaschaEnde\Users\Domain\Repository\NewsRepository;
use SaschaEnde\Users\Domain\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use t3h\t3h;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserGroupRepository;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class RssCommandController extends Command {

    /**
     * Configure the command by defining the name, options and arguments
     */
    public function configure() {
        $this->addArgument('rootPid', InputArgument::REQUIRED, 'Website root page uid');
        $this->addArgument('pagePid', InputArgument::REQUIRED, 'Page uid for new news');
        $this->setDescription('Importiere RSS Feeds');
        $this->setHelp('Neue Feed Daten werden importiert.');
    }

    /**
     * Abgleich der Daten
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output) {

        /** @var UserRepository $userRepository */
        $userRepository = t3h::injectClass(UserRepository::class);
        $userRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        /** @var FrontendUserGroupRepository $frontendUserGroupRepository */
        $frontendUserGroupRepository = t3h::injectClass(FrontendUserGroupRepository::class);
        $frontendUserGroupRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        /** @var NewsRepository $newsRepository */
        $newsRepository = t3h::injectClass(NewsRepository::class);
        $newsRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        /** @var NewsCategoryRepository $newsCategoryRepository */
        $newsCategoryRepository = t3h::injectClass(NewsCategoryRepository::class);
        $newsCategoryRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        /** @var CompanyRepository $companyRepository */
        $companyRepository = t3h::injectClass(CompanyRepository::class);
        $companyRepository->setDefaultQuerySettings(t3h::Database()->getQuerySettings());

        $rssfeeds = t3h::Settings()->setCurrentPageId($input->getArgument('rootPid'))->getExtension('tx_users')['rssfeeds.'];

        // Für jeden RSS Feed
        foreach ($rssfeeds as $feed) {

            // Lade Feed und parse Daten
            $content = file_get_contents($feed['url']);
            $rss = simplexml_load_string($content) or die("Error: Cannot create object");
            $rss = t3h::Data()->objectToArray($rss);

            // Wenn Daten existieren
            if (is_array($rss['channel']['item'])) {

                // Für jede News
                foreach ($rss['channel']['item'] as $item) {

                    // Hash aus Feed URL und News URL
                    $rssHash = md5($feed['url'] . $item['guid']);

                    // Weiter, wenn diese News bereits importiert wurde
                    if($newsRepository->findOneByRssHash($rssHash) != null){
                        continue;
                    }

                    $newsObj = new News();
                    $newsObj->setTitle($item['title']);
                    $newsObj->setContent($item['description']);
                    $newsObj->setShortcontent($item['description']);
                    $newsObj->setCrdate(new \DateTime($item['pubDate']));
                    $newsObj->setPid($input->getArgument('pagePid'));
                    $newsObj->setRssHash($rssHash);

                    // Kategorien
                    $categories = new ObjectStorage();
                    foreach(explode(',',$feed['categories']) as $catUid){
                        $categories->attach($newsCategoryRepository->findByUid($catUid));
                    }
                    if($categories->count() >= 1){
                        $newsObj->setCategories($categories);
                    }

                    // Gruppen
                    $groups = new ObjectStorage();
                    foreach(explode(',',$feed['groups']) as $groupUid){
                        $groups->attach($frontendUserGroupRepository->findByUid($groupUid));
                    }
                    if($groups->count() >= 1){
                        $newsObj->setGroups($groups);
                    }

                    // User?
                    if(!empty($feed['user'])){
                        $user = $userRepository->findByUid($feed['user']);
                        $newsObj->setUser($user);
                    }

                    // Highlight?
                    if($feed['highlight'] == 1){
                        $newsObj->setHighlight(true);
                    }

                    // Firma?
                    if(!empty($feed['company'])){
                        $newsObj->setCompany($companyRepository->findByUid($feed['company']));
                    }

                    // Füge News hinzu, wenn diese noch nicht existiert
                    $newsRepository->add($newsObj);
                    t3h::Database()->persistAll();

                    DebuggerUtility::var_dump($newsObj);
                }
            }
        }


    }


}