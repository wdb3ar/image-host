<?php

class File
{
    private static $directory = '/upload/';

    public $name;
    private $newName;
    private $directoryPath;
    private $file;



    public function getDirectoryPath()
    {
        if (!$this->directoryPath) {
            $this->directoryPath = $_SERVER['DOCUMENT_ROOT'] . self::$directory;
        }
        return $this->directoryPath;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Generate a unique name for the file
     * @return string
     */
    public function getNewName()
    {
        while (true) {
            $newName = uniqid(rand(), true) . '.' . pathinfo($this->file["name"], PATHINFO_EXTENSION);
            if (!file_exists($this->getDirectoryPath() . $newName)) {
                return $newName;
                break;
            }
        }
    }

    public function save()
    {
        $this->name = $this->getNewName();
        if (move_uploaded_file($this->file["tmp_name"], $this->getDirectoryPath() . $this->name)) {
            return true;
        }
        return false;
    }

    public function delete($name = null)
    {
        $name = $name ?: $this->name;
        if (file_exists($this->getDirectoryPath() . $name)) {
            unlink($this->getDirectoryPath() . $name);
            return true;
        }
        return false;
    }

    public static function getFile($name)
    {
        return self::$directory . $name;
    }
}
