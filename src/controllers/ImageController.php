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
        $uploadedFile = array_key_exists('inputFile', $_FILES) ? $_FILES['inputFile'] : null;
        $tags = array_key_exists('tags', $_POST) ? $_POST['tags'] : null;
        if ($tags) {
            //Convert a string to an array, and delete the empty elements
            $tags = array_diff(explode(',', $_POST['tags']), ['']);
        }

        $validator = new Validator;
        $validator->validateImage($uploadedFile);
        $validator->validateTags($tags);
        if ($validator->errors) {
            $data['errors'] = $validator->getHtmlErrors();
        } else {
            $file = new File();
            $file->setFile($uploadedFile);
            if ($file->save()) {
                $image = new Image($uploadedFile['name'], $file->getNewName());
                try {
                    $pdo = $this->container->getPdo();
                    $dataGateway = new DataGateway($pdo);
                    $pdo->beginTransaction();
                    $result = $dataGateway->saveImageAndTags($image->name, $image->path, $tags);
                    if (!$result) {
                        throw new Exception('Failed to save data to database');
                    }
                    $pdo->commit();
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $file->delete();
                    throw $e;
                }
            }
        }

        return $this->view->generate('add', $data);
    }

    public function actionEdit($id)
    {
    }
}
