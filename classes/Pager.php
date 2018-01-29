<?php
class Pager
{
    public static $recordsPerPage;
    public $page;
    public $totalPages;
    private $totalRecords;

    public function __construct($totalRecords)
    {
        $this->page = self::getPage();
        $container = Container::getInstance();
        self::$recordsPerPage = $container->config['records_per_page'];
        $this->totalRecords = $totalRecords;
        $this->totalPages = ceil($totalRecords / self::$recordsPerPage);
    }

    public function getOffset()
    {
        return self::$recordsPerPage * $this->page - self::$recordsPerPage;
    }

    public static function getPage()
    {
      return array_key_exists('page', $_GET) && Validator::checkId($_GET['page']) ? intval($_GET['page']) : 1;
    }

    public static function getPageSize()
    {
      return array_key_exists('pageSize', $_GET) && Validator::checkId($_GET['pageSize']) ? intval($_GET['pageSize']) : self::$recordsPerPage;
    }

    public function isFoundImgForPage()
    {
      return $this->page <= $this->totalPages;
    }
}
