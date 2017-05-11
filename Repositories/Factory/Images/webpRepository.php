<?php
/**
* 
* @Author: jianghonggang
* 
*/

namespace App\Repositories\Factory\Images;
use App\Repositories\BaseRepository as Base;

class webpRepository implements imageRepository{
 
	public function __construct(){
		
	}


	public function resize_image($imageinfo,$size){
		$nameList = explode(".", $data->image_name);
		$realpath=base_path().'/'.$imageinfo['url'];
		if($nameList[1] == "jpg" || $nameList[1] == "jpeg")
			$im = imagecreatefromjpeg($realpath);
		elseif($nameList[1] == "png")
			$im = imagecreatefrompng($realpath);
		elseif($nameList[1] == "webp")
			$im = imagecreatefromwebp($realpath);
		$size=$this->getSize($data,$im);
		$im2 = imagecreatetruecolor($size['nx'], $size['ny']);
		foreach ($size['new'] as $key => $value) {
			imagecopyresized($im2, $im, 0, 0, 0, 0, floor($value['nw']), floor($value['ny']), $size['old']['x'], $size['old']['y']);
			Factory::getImageClass('redis')->getRedis()->lpush($data['imageId'].$size['sizeType'],$im2);
			if($key==0){
				$toshow=$im2;
			}
		}
		imagewebp($im, $nameList[0].'.webp');
	}

	public function getSize($data,$im){
		$wc=strpos($data['size'],'w');
		$data['x'] = imagesx($im);
		$data['y'] = imagesy($im);

		if($wc===false) {
			$size=re_pleace('h','',$data['size']);
			$data['nh'] = $size;
			$data['nw'] = $data['y'] * $data['x']/$data['y'];
		}
		else {
			$size=re_pleace('w','',$data['size']);
			$data['nw'] = $size;
			$data['nh'] = $data['y'] / $data['x'] * $data['w'] ;
		}
		return $data;

	}
		

}
