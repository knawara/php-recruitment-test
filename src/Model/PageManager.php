<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{
    CONST ASC  = 'ASC';
    CONST DESC = 'DESC';

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function setLastPageVisit(Page $page)
    {
        $dateTime = date('Y-m-d H:i:s');

        $pageId = $page->getPageId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('UPDATE pages SET last_page_visit = :lastPageVisit WHERE page_id = :pageId');
        $statement->bindParam(':lastPageVisit', $dateTime, \PDO::PARAM_STR);
        $statement->bindParam(':pageId', $pageId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function getUserPagesTotal(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('SELECT COUNT(pages.website_id) FROM pages INNER JOIN websites ON pages.website_id=websites.website_id WHERE websites.user_id = :userId;');
        $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return (int)$statement->fetchColumn();
    }

    public function getUserLeastRecentlyVisitedPage(User $user)
    {
        return $this->getOrderByDateUserVisitedPage($user, self::ASC);
    }

    public function getUserMostRecentlyVisitedPage(User $user)
    {
         return $this->getOrderByDateUserVisitedPage($user, self::DESC);
    }

    public function getOrderByDateUserVisitedPage(User $user, $orderBy)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('SELECT websites.hostname, pages.url FROM pages INNER JOIN websites ON pages.website_id=websites.website_id WHERE websites.user_id = :userId ORDER BY pages.last_page_visit ' . $orderBy . ';');
        $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchObject(Page::class);
    }
}