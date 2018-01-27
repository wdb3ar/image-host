<?php

class NotFoundException extends Exception
{
    public function showNotFoundPage()
    {
        header('HTTP/1.1 404 Not Found');
        $template = 'not-found';
        include  __DIR__.'/../../views/layout.php';
    }
}
