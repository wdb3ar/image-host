<?php

class View
{
    public function generate($template, $data = null)
    {
        if (is_array($data)) {
            extract($data);
        }

        function html($string) {
          return htmlspecialchars($string, ENT_QUOTES);
        }

        $parsedUri = parse_url($_SERVER['REQUEST_URI']);
        $uriPath = $parsedUri['path'];

        include  __DIR__.'/../views/layout.php';
    }

}
