<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        $dataGateway = new DataGateway($this->container->getDbh());
        $imagesCount = $dataGateway->getCountImages();
        $imagesCount = $imagesCount['count'];
        $pager = new Pager($imagesCount);
        if ($pager->page>1 && !$pager->isFoundImgForPage()) {
            throw new NotFoundException('No images found for the current page');
        }
        $images = $dataGateway->getImagesWithTags($pager);

        return $this->view->generate('index', ['images' => $images, 'pager' => $pager]);
    }

    public function actionSearch()
    {
        $query = FormData::getQuery();
        $dataGateway = new DataGateway($this->container->getDbh());
        $imagesCount = $dataGateway->getCountImagesByQuery($query);
        $imagesCount = $imagesCount['count'];
        $pager = new Pager($imagesCount);
        if ($pager->page>1 && !$pager->isFoundImgForPage()) {
            throw new NotFoundException('No images found for the current page');
        }
        $images = $dataGateway->getImagesWithTagsByQuery($query, $pager);

        return $this->view->generate('index', ['images' => $images, 'query' => $query, 'pager' => $pager]);
    }
}
