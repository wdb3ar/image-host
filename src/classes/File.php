<?php

class File
{
    public $newName;
    static private $directory = '/upload/';
    private $directoryPath;
    private $file;


    public function __construct($file)
    {
        $this->directoryPath = $_SERVER['DOCUMENT_ROOT'] . $this->directory;
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
            if (!file_exists($this->directoryPath . $newName)) {
                break;
            }
        }
        return $newName;
    }

    public function save()
    {
        $this->newName = $this->getNewName();
        if (move_uploaded_file($this->file["tmp_name"], $this->directoryPath . $this->newName)) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        unlink($this->directoryPath . $this->newName);
    }

    static public function getFile($name)
    {
      return self::$directory . $name;
    }
}
