<?php

class FormData
{
    public static function getTags()
    {
        $tags = array_key_exists('tags', $_POST) ? $_POST['tags'] : null;
        if ($tags) {
            //Convert a string to an array, and delete the empty elements
            $tags = array_diff(explode(',', $_POST['tags']), ['']);
            $tags = array_unique($tags);

            foreach ($tags as $key => $val) {
                $tags[$key] = trim($val);
            }
        }

        return $tags;
    }

    public static function getUploadedFile()
    {
        return array_key_exists('inputFile', $_FILES) ? $_FILES['inputFile'] : null;
    }

    public static function getImageId()
    {
        return array_key_exists('imageId', $_POST) ? $_POST['imageId'] : null;
    }
}
