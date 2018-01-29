<?php

class ApiController extends Controller
{
    public function actionIndex()
    {
        $tag = array_key_exists('tag', $_GET) ? $_GET['tag'] : null;
        $page = Pager::getPage();
        $pageSize = array_key_exists('pageSize', $_GET) ? $_GET['pageSize'] : $this->container->config['records_per_page'];
        $dataGateway = new DataGateway($this->container->getDbh());
        if ($tag) {
            $imagesCount = $dataGateway->getCountImagesByQuery($tag);
        } else {
            $imagesCount = $dataGateway->getCountImages();
        }
        $imagesCount = $imagesCount['count'];
        $pager = new Pager($imagesCount);
        if ($tag) {
            $images = $dataGateway->getImagesWithTagsByQuery($tag, $pager);
        } else {
            $images = $dataGateway->getImagesWithTags($pager);
        }
        if ($images) {
            $convertObjToArr = function ($item) {
                $arr['id'] = $item->id;
                $arr['url'] = Router::getProtocol() . $_SERVER['HTTP_HOST'] . File::getFile($item->path);
                $arr['name'] = $item->name;
                $arr['tags'] = $item->tag_names;
                return $arr;
            };
            $images = array_map($convertObjToArr, $images);
        }
        exit(json_encode($images, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
