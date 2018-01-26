<?php

class File
{
  public $newName;
  private $directory;
  private $file;


  public function __construct($file) {
    $this->file = $file;
    $this->directory = $_SERVER['DOCUMENT_ROOT'] . '/upload/';
  }

/**
 * Generate a unique name for the file
 * @return string
 */
  public function getNewName() {
    while (true) {
        $newName = uniqid(rand(), true) . '.' . pathinfo($this->file["name"], PATHINFO_EXTENSION);
        if (!file_exists($this->directory . $newName)) {
          break;
        }
    }
    return $newName;
  }

  public function save() {
    $this->newName = $this->getNewName();
    if (move_uploaded_file($this->file["tmp_name"], $this->directory . $this->newName)) {
      return true;
    }
    return false;
  }

  public function delete() {
    unlink($this->directory . $this->newName);
  }
}
