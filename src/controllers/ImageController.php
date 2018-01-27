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
                    $image = new Image($uploadedFile['name'], $file->getNewName());
                    $dbh = $this->container->getDbh();
                    $dataGateway = new DataGateway($dbh);
                    $dbh->beginTransaction();
                    $imageId = $dataGateway->saveImageAndTags($image->name, $image->path, $tags);
                    if (!$imageId) {
                        throw new Exception('Failed to save data to database');
                    }
                    $dbh->commit();
                    header('Location: /edit/'.$imageId);
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $file->delete();
                    throw $e;
                }
            }
        }

        return $this->view->generate('add', $data);
    }

    public function actionEdit($imageId)
    {
        if (!preg_match("/^[1-9]([0-9]+)?$/u", $imageId)) {
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
        if (!preg_match("/^[1-9]([0-9]+)?$/u", $imageId)) {
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
                if ($dataGateway->saveImageNewTags($imageId, $tags)) {
                    $dbh->commit();
                    header('Location: /edit/'.$imageId);
                }
            } catch (Exception $e) {
                $dbh->rollBack();
                $file->delete();
                throw $e;
            }
        }

        return $this->view->generate('edit', $data);
    }
}
