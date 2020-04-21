<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(
        Database $database
    ) {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->addLastPageVisitFieldToPagesTable();
    }

    private function addLastPageVisitFieldToPagesTable()
    {
        $createQuery = <<<SQL
ALTER TABLE `pages` 
ADD COLUMN `last_page_visit` TIMESTAMP NULL DEFAULT NULL ;
SQL;
        $this->database->exec($createQuery);
    }
}