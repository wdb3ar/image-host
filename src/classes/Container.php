<?php

final class Container
{
    private static $instance;

    public $config;
    private $pdo;

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo()
    {
        if (!$this->pdo) {
            $this->pdo = new PDO(
              "mysql:host=" . $this->config['host'] . ";dbname=" . $this->config['dbname'] . ";charset=" . $this->config['charset'],
              $this->config['user'],
              $this->config['pass']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->pdo;
    }


    private function __construct()
    {
    }


    private function __clone()
    {
    }


    private function __sleep()
    {
    }


    private function __wakeup()
    {
    }
}
