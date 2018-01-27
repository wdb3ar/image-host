<?php

abstract class Controller
{
    protected $view;
    protected $container;

    public function __construct()
    {
        $this->view = new View();
        $this->container = Container::getInstance();
    }
}
