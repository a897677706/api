<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Repositories\Api\imagesRepository as imageR;

class ImagesController extends ApiController
{
    /**
     * 通过中间件imagecache进行缓存查询如果命中就输出缓存，未命中就走sql查询
     *
     * 
     */
    public function GetImage()
    {
        list($imageId,$size,$type)=func_get_args();
        $imageR=new imageR;
        $imageR->begin($imageId,$size,$type);
      	// return view('admin.system.index');
    }
}
