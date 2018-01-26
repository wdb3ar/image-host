<?php

/**
 * A class that contains methods for working with the image table.
 */
class DataGateway
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

    public function getImagesWithTags()
    {
        $query = $this->pdo->query(
          'SELECT i.*, GROUP_CONCAT(t.id) as tag_ids, GROUP_CONCAT(t.name) as tag_names
          FROM image AS i
          JOIN image_tag AS it
          ON i.id = it.image_id
          JOIN tag AS t
          on t.id = it.tag_id
          group by i.id'
        );
        $query->setFetchMode(PDO::FETCH_CLASS, 'Image');
        return $query->fetchAll();
    }

    public function saveImage($name, $path)
    {
        $query = $this->pdo->prepare(
        'INSERT INTO image (name, path) values (:name, :path)'
      );
        $query->bindParam(':name', $name);
        $query->bindParam(':path', $path);
        if ($query->execute()) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function saveTags($tags)
    {
        $insertValues = [];
        foreach ($tags as $tag) {
            $questionMarks[] = '(?)';
            $insertValues[] = $tag;
        }
        $query = $this->pdo->prepare(
          'INSERT INTO tag (name) VALUES ' . implode(',', $questionMarks)
        );
        if ($query->execute($insertValues)) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function saveImageAndTags($imageName, $imagePath, $tags)
    {
        if (($imageId = $this->saveImage($imageName, $imagePath)) && ($firstTagId = $this->saveTags($tags))) {
            // Create an array of tag IDs
            $tagIds=range($firstTagId, $firstTagId+count($tags)-1);
            $insertValues = [];
            foreach ($tagIds as $tagId) {
                $questionMarks[] = '(?,?)';
                $insertValues = array_merge($insertValues, [$imageId, $tagId]);
            }
            $query = $this->pdo->prepare(
            'INSERT INTO image_tag (image_id, tag_id) VALUES ' . implode(',', $questionMarks)
          );
            return $query->execute($insertValues);
        }
    }
}
