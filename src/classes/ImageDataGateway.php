<?php

/**
 * A class that contains methods for working with the image table.
 */
class ImageDataGateway
{
    public $errors=[];
    private $pdo;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getImages()
    {
        $query = $this->pdo->query('SELECT * from image');
        $query->setFetchMode(PDO::FETCH_CLASS, 'Image');
        return $query->fetchAll();
    }
}
