<?php

final class Container
{
    private static $instance;

    public $pdo;
    public $config;

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
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
