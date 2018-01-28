<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        $dataGateway = new DataGateway($this->container->getDbh());
        $images = $dataGateway->getImagesWithTags();

        return $this->view->generate('index', ['images' => $images]);
    }

    public function actionSearch()
    {
        $query = FormData::getQuery();
        $dataGateway = new DataGateway($this->container->getDbh());
        $images = $dataGateway->getImagesWithTagsByQuery($query);
        return $this->view->generate('index', ['images' => $images, 'query' => $query]);
    }
}
