<?php

class ImageController extends Controller
{
    public function actionAdd()
    {
        return $this->view->generate('add');
    }

    public function actionAddPost()
    {
        $data = [];

        $uploadedFile = FormData::getUploadedFile();
        $tags = FormData::getTags();

        $validator = new Validator;
        $validator->validateImage($uploadedFile);
        $validator->validateTags($tags);
        if ($validator->errors) {
            $data['errors'] = $validator->getHtmlErrors();
        } else {
            $file = new File();
            $file->setFile($uploadedFile);
            if ($file->save()) {
                try {
                    $image = new Image($uploadedFile['name'], $file->name);
                    $dbh = $this->container->getDbh();
                    $dataGateway = new DataGateway($dbh);
                    $dbh->beginTransaction();
                    $imageId = $dataGateway->saveImageAndTags($image->name, $image->path, $tags);
                    if (!$imageId) {
                        throw new Exception('Failed to save data to database');
                    }
                    $dbh->commit();
                    header('Location: /edit/'.$imageId);
                    exit();
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $file->delete();
                    throw $e;
                }
            }
            throw new Exception("Could not save file");
        }

        return $this->view->generate('add', $data);
    }

    public function actionEdit($imageId)
    {
        if (!Validator::checkId($imageId)) {
            throw new NotFoundException('The variable must be an integer');
        }
        $dataGateway = new DataGateway($this->container->getDbh());
        $image = $dataGateway->getImageWithTagsById($imageId);
        if (!$image) {
            throw new NotFoundException('Image with this id is not found');
        }

        return $this->view->generate('edit', ['image'=>$image]);
    }

    public function actionEditPost($imageId)
    {
        if (!Validator::checkId($imageId)) {
            throw new NotFoundException('The variable must be an integer');
        }

        $dbh = $this->container->getDbh();
        $dataGateway = new DataGateway($dbh);
        $data['image'] = $dataGateway->getImageWithTagsById($imageId);
        if (!$data['image']) {
            throw new NotFoundException('Image with this id is not found');
        }

        $tags = FormData::getTags();

        $validator = new Validator;
        $validator->validateTags($tags);
        if ($validator->errors) {
            $data['errors'] = $validator->getHtmlErrors();
        } else {
            try {
                $dbh->beginTransaction();
                if ($dataGateway->editImageTags($imageId, $tags)) {
                    $dbh->commit();
                    header('Location: /edit/'.$imageId);
                    exit();
                }
                throw new Exception('Failed to save data to database');
            } catch (Exception $e) {
                $dbh->rollBack();
                if ($e instanceof TagEditException) {
                    header('Location: /edit/'.$imageId);
                    exit();
                }
                throw $e;
            }
        }

        return $this->view->generate('edit', $data);
    }

    public function actionDeletePost()
    {
        $imageId = FormData::getImageId();
        if (!Validator::checkId($imageId)) {
            throw new NotFoundException('The variable imageId must be an integer');
        }
        $dbh = $this->container->getDbh();
        $dataGateway = new DataGateway($dbh);
        $image = $dataGateway->getImageById($imageId);
        if (!$image) {
            throw new NotFoundException('Image with this id not found');
        }
        try {
            $dbh->beginTransaction();
            if ($dataGateway->deleteImageWithTags($imageId)) {
                $dbh->commit();
                $file = new File();
                $file->delete($image->path);
                exit(json_encode(['status'=>true]));
            }
            throw new Exception('Failed to delete image with tags in the database');
        } catch (Exception $e) {
            $dbh->rollBack();
            throw $e;
        }
    }
}
