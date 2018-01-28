<?php

class View
{
    public function generate($template, $data = null)
    {
        if (is_array($data)) {
            extract($data);
        }

        include  __DIR__.'/../views/layout.php';
    }

}
