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


    public function getImageById($imageId)
    {
        $sth = $this->dbh->prepare('SELECT * FROM image WHERE id = ?');
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Image');
        if (!$sth->execute([$imageId])) {
            return false;
        }
        return $sth->fetch();
    }

    public function getImageWithTagsById($imageId)
    {
        $sth = $this->dbh->prepare(
        'SELECT i.*, GROUP_CONCAT(t.id) AS tag_ids, GROUP_CONCAT(t.name) AS tag_names
        FROM image AS i
        JOIN image_tag AS it
        ON i.id = image_id AND image_id = ?
        JOIN tag AS t
        ON t.id = tag_id
        GROUP BY i.id'
      );
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Image');
        if (!$sth->execute([$imageId])) {
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
        $sth = $this->dbh->prepare('SELECT * FROM tag WHERE name IN ('.implode(',', $questionMarks).')');

        $sth->setFetchMode(PDO::FETCH_CLASS, 'Tag');

        var_dump($sth, $tags, $sth->execute($tags), $sth->fetchAll());
        if ($sth->execute($tags)) {
            return $sth->fetchAll();
        }
        return false;
    }

    public function gatTagsByImageId($imageId)
    {
        $sth = $this->dbh->prepare('SELECT tag_id AS id, name FROM image_tag JOIN tag on tag_id = tag.id WHERE image_id = ?');
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Tag');
        if ($sth->execute([$imageId])) {
            return $sth->fetchAll();
        }
        return false;
    }

    public function deleteImageById($imageId)
    {
        $sth = $this->dbh->prepare(
          'DELETE FROM image WHERE id = ?'
        );
        return $sth->execute([$imageId]);
    }

    public function deleteRelations($imageId)
    {
        $sth = $this->dbh->prepare(
          'DELETE FROM image_tag WHERE image_id = ?'
        );
        return $sth->execute([$imageId]);
    }

    public function deleteUnusedTags()
    {
        $sth = $this->dbh->prepare(
        'DELETE tag FROM tag LEFT JOIN image_tag ON tag.id = tag_id WHERE  tag_id IS NULL'
      );
        return $sth->execute();
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
        if ($imageId = $this->saveImage($imageName, $imagePath)) {
            $foundsTags = $this->getTagsByNames($tags);
            if ($foundsTags) {
                foreach ($foundsTags as $tag) {
                    $foundsTagsArr[$tag->id] = $tag->name;
                }
                $tagsDiff = array_diff($tags, $foundsTagsArr);
                if ($tagsDiff) {
                    $firstTagId = $this->saveTags($tagsDiff);
                    if (!$firstTagId) {
                        return false;
                    }
                    // Create an array of tag IDs
                    $tagIds = range($firstTagId, $firstTagId+count($tagsDiff)-1);
                    $tagIds = array_merge($tagIds, array_keys($foundsTagsArr));
                } else {
                    $tagIds = array_keys($foundsTagsArr);
                }
            } else {
                $firstTagId = $this->saveTags($tags);
                if (!$firstTagId) {
                    return false;
                }
                // Create an array of tag IDs
                $tagIds = range($firstTagId, $firstTagId+count($tags)-1);
            }
            if ($this->saveImageTagsRelations($imageId, $tagIds)) {
                return $imageId;
            }
        }
        return false;
    }

    public function editImageTags($imageId, $tags)
    {
        $imageTags = $this->gatTagsByImageId($imageId);

        foreach ($imageTags as $key => $val) {
            $imageTagsArr[$val->id] = $val->name;
        }

        if (!(array_diff($imageTagsArr, $tags) || array_diff($tags, $imageTagsArr))) {
            throw new TagEditException('Tags have not been changed');
        }

        if (!$this->deleteRelations($imageId)) {
            return false;
        }

        $foundsTags = $this->getTagsByNames($tags);

        if ($foundsTags) {
            foreach ($foundsTags as $tag) {
                $foundsTagsArr[$tag->id] = $tag->name;
            }

            $tagsDiff = array_diff($tags, $foundsTagsArr);
            $tagIds=[];
            if ($tagsDiff) {
                $firstTagId = $this->saveTags($tagsDiff);
                if (!$firstTagId) {
                    return false;
                }
                // Create an array of tag IDs
                $tagIds = range($firstTagId, $firstTagId+count($tagsDiff)-1);
            }
            $imageTagsIds = array_keys($imageTagsArr);
            $removedTagIds = array_diff($imageTagsIds, array_keys($foundsTagsArr));
            $tagIds = array_merge($tagIds, $imageTagsIds);
            $tagIds = array_diff($tagIds, $removedTagIds);
            if ($this->saveImageTagsRelations($imageId, $tagIds) && $this->deleteUnusedTags()) {
                return true;
            }
        }
        return false;
    }

    public function deleteImageWithTags($imageId)
    {
        if ($this->deleteImageById($imageId) && $this->deleteUnusedTags()) {
            return true;
        }
        return false;
    }
}
