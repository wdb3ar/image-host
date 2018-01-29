<?php
class Pager
{
    public $page;
    public $recordsPerPage;
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
}
