<?php
/**
* 
* @Author: jianghonggang
* 
*/

namespace App\Repositories\Api;
use App\Repositories\BaseRepository as Base;
use App\Repositories\Factory\FactoryRepository as Factory;
use App\Models\Image as Img;

class imagesRepository extends Base{
 
	public function __construct(){
		
	}

	/**
     * 主要image处理
     *
     * 
     */
	public function begin($imageId,$size,$type){
		$webp = strpos($_SERVER['HTTP_ACCEPT'], 'image/webp');
		$imageM=new Img;
		$imageinfo=$imageM->where('image_id',$imageId)->get()->toArray();
		$data['imageId']=$imageId;
		$data['size']=$size;
		$data['type']=$type;
		Factory::getImageClass($data['type'])->resize_image($imageinfo[0],$data);
		
	}
	

}
