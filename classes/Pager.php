<?php
class Pager
{
    public $recordsPerPage;
    public $page;
    public $totalPages;
    private $totalRecords;

    public function __construct($totalRecords)
    {
        $this->page = self::getPage();
        $container = Container::getInstance();
        $this->recordsPerPage = $container->config['records_per_page'];
        $this->totalRecords = $totalRecords;
        $this->totalPages = ceil($totalRecords / $this->recordsPerPage);
    }

    public function getOffset()
    {
        return $this->recordsPerPage * $this->page - $this->recordsPerPage;
    }

    public static function getPage()
    {
      return array_key_exists('page', $_GET) && Validator::checkId($_GET['page']) ? intval($_GET['page']) : 1;
    }

    public static function getPageSize()
    {
      return array_key_exists('pageSize', $_GET) && Validator::checkId($_GET['pageSize']) ? intval($_GET['pageSize']) : null;
    }

    public function isFoundImgForPage()
    {
      return $this->page <= $this->totalPages;
    }
}
