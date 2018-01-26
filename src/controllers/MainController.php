<?php

class MainController extends Controller
{
    public function actionIndex()
    {
        $container = Container::getInstance();
        $dataGateway = new DataGateway($container->pdo);
        $images = $dataGateway->getImagesWithTags();

        return $this->view->generate('index', ['images' => $images]);
    }

    public function actionAdd()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                $errors = $validator->getHtmlErrors();
            } else {
                $file = new File($uploadedFile);
                if ($file->save()) {
                    $image = new Image($uploadedFile['name'], $file->newName);
                    $container = Container::getInstance();
                    try {
                        $dataGateway = new DataGateway($container->pdo);
                        if (!$dataGateway->saveImageAndTags($image->name, $image->path, $tags)) {
                            throw new Exception('Server error');
                        }
                    } catch (Exception $e) {
                        $file->delete();
                        throw $e;
                    }
                }
            }
        }
        return $this->view->generate('add', ['errors' => $errors]);
    }
}
