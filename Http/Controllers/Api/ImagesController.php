<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Repositories\Api\imagesRepository as imageR;

class ImagesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetImage()
    {
        list($imageId,$size,$type)=func_get_args();
        $imageR=new imageR;
        $imageR->begin($imageId,$size,$type);
      	// return view('admin.system.index');
    }
}
