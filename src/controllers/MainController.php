<?php

class MainController
{
    public function actionIndex()
    {
        $container = Container::getInstance();
        $imageDataGateway = new ImageDataGateway($container->pdo);
        $images = $imageDataGateway->getImages();
    }
}
