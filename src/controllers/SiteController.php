<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        $dataGateway = new DataGateway($this->container->getPdo());
        $images = $dataGateway->getImagesWithTags();

        return $this->view->generate('index', ['images' => $images]);
    }


}
