<?php

class MainController extends Controller
{
    public function actionIndex()
    {
        $container = Container::getInstance();
        $imageDataGateway = new ImageDataGateway($container->pdo);
        $images = $imageDataGateway->getImages();

        return $this->view->generate('index');
    }

    public function actionAdd()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $file = array_key_exists('inputFile', $_FILES) ? $_FILES['inputFile'] : null;
            $validator = new Validator;
            $validator->validateImage($file);
            if ($validator->errors) {
                $errors = $validator->getHtmlErrors();
            }
        }
        return $this->view->generate('add', ['errors' => $errors]);
    }
}
