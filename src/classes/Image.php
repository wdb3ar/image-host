<?php

class Image
{
    public $id;
    public $name;
    public $path;
    public $tag_ids;
    public $tag_names;

    public function __construct($name = null, $path = null)
    {
        if ($name) {
            $this->name = $name;
        }
        if ($path) {
            $this->path = $path;
        }
    }
}
