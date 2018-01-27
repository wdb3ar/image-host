<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        $dataGateway = new DataGateway($this->container->getDbh());
        $images = $dataGateway->getImagesWithTags();

        return $this->view->generate('index', ['images' => $images]);
    }
    
    public function actionNotFound()
    {
        return $this->view->generate('not-found');
    }
}
