<?php

/**
 * A class that contains methods for working with the image table.
 */
class DataGateway
{
    public $errors=[];
    private $dbh;
    public function __construct(\PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    public function getImages()
    {
        $sth = $this->dbh->query('SELECT * FROM image');
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Image');
        return $sth->fetchAll();
    }

    public function getImageWithTagsById($id)
    {
        $sth = $this->dbh->prepare(
        'SELECT i.*, GROUP_CONCAT(t.id) AS tag_ids, GROUP_CONCAT(t.name) AS tag_names
        FROM image AS i
        JOIN image_tag AS it
        ON i.id = image_id AND image_id = :id
        JOIN tag AS t
        ON t.id = tag_id
        GROUP BY i.id'
      );
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Image');
        if (!$sth->execute()) {
            return false;
        }
        return $sth->fetch();
    }

    public function getImagesWithTags()
    {
        $sth = $this->dbh->query(
          'SELECT i.*, GROUP_CONCAT(t.id) AS tag_ids, GROUP_CONCAT(t.name) AS tag_names
          FROM image AS i
          JOIN image_tag AS it
          ON i.id = it.image_id
          JOIN tag AS t
          ON t.id = it.tag_id
          GROUP BY i.id'
        );
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Image');
        return $sth->fetchAll();
    }

    public function getTagsByNames($tags)
    {
        $insertValues = [];
        foreach ($tags as $tag) {
            $questionMarks[] = '?';
            $insertValues = array_merge($insertValues, [$tag]);
        }
        $sth = $this->dbh->prepare('SELECT * FROM tag WHERE name in ('.implode(',', $questionMarks).')');

        $sth->setFetchMode(PDO::FETCH_CLASS, 'Tag');

        if ($sth->execute($insertValues)) {
            return $sth->fetchAll();
        }
        return false;
    }

    public function saveImage($name, $path)
    {
        $sth = $this->dbh->prepare(
        'INSERT INTO image (name, path) VALUES (?, ?)'
        );

        if ($sth->execute([$name, $path])) {
            return $this->dbh->lastInsertId();
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
        $sth = $this->dbh->prepare(
          'INSERT INTO tag (name) VALUES ' . implode(',', $questionMarks)
        );
        if ($sth->execute($insertValues)) {
            return $this->dbh->lastInsertId();
        }
        return false;
    }

    public function saveImageTagsRelations($imageId, $tagIds)
    {
        $insertValues = [];
        foreach ($tagIds as $tagId) {
            $questionMarks[] = '(?,?)';
            $insertValues = array_merge($insertValues, [$imageId, $tagId]);
        }
        $sth = $this->dbh->prepare(
        'INSERT INTO image_tag (image_id, tag_id) VALUES ' . implode(',', $questionMarks)
        );
        if ($sth->execute($insertValues)) {
            return true;
        }
        return false;
    }

    public function saveImageAndTags($imageName, $imagePath, $tags)
    {
        if (($imageId = $this->saveImage($imageName, $imagePath)) && ($firstTagId = $this->saveTags($tags))) {
            // Create an array of tag IDs
            $tagIds=range($firstTagId, $firstTagId+count($tags)-1);
            if ($this->saveImageTagsRelations($imageId, $tagIds)) {
                return $imageId;
            }
        }
        return false;
    }

    public function saveImageNewTags($imageId, $tags)
    {
        $foundsTags = $this->getTagsByNames($tags);
        if ($foundsTags) {
            foreach ($foundsTags as $tag) {
                $foundsTagsNew[$tag->id] = $tag->name;
            }
            $tagsDiff = array_diff($tags, $foundsTagsNew);
            if ($tagsDiff && ($firstTagId = $this->saveTags($tagsDiff))) {
                $tagIds=range($firstTagId, $firstTagId+count($tagsDiff)-1);
                if ($this->saveImageTagsRelations($imageId, $tagIds)) {
                    return true;
                }
            }
        }
        return false;
    }
}
