<?php

class File
{
    private static $directory = '/upload/';

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
        if (!$this->newName) {
            while (true) {
                $newName = uniqid(rand(), true) . '.' . pathinfo($this->file["name"], PATHINFO_EXTENSION);
                if (!file_exists($this->getDirectoryPath() . $newName)) {
                    $this->newName = $newName;
                    break;
                }
            }
        }
        return $this->newName;
    }

    public function save()
    {
        if (move_uploaded_file($this->file["tmp_name"], $this->getDirectoryPath() . $this->getNewName())) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        if (file_exists($this->getDirectoryPath() . $this->getNewName())) {
            unlink($this->getDirectoryPath() . $this->getNewName());
            return true;
        }
        return false;
    }

    public static function getFile($name)
    {
        return self::$directory . $name;
    }
}
