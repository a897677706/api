<?php
/**
* 
* @Author: jianghonggang
* 
*/

namespace App\Repositories\Factory\Images;
use App\Repositories\BaseRepository as Base;
use App\Repositories\Factory\FactoryRepository as Factory;

class jpgRepository implements imageRepository{
 
	public function __construct(){
		
	}


	public function resize_image($imageinfo,$data){
		$realpath=base_path().'/'.$imageinfo['url'];
		$im = imagecreatefromjpeg($realpath);
		$size=$this->getSize($data,$im);
		$im2 = imagecreatetruecolor($size['nx'], $size['ny']);
		foreach ($size['new'] as $key => $value) {
			imagecopyresized($im2, $im, 0, 0, 0, 0, floor($value['nw']), floor($value['ny']), $size['old']['x'], $size['old']['y']);
			Factory::getImageClass('redis')->getRedis()->hset($data['imageId'].$size['sizeType'],$size['sizeType']=='w'?$value['ny']:$value['nx'],$im2);
			if($key==0){
				$toshow=$im2;
			}
		}
		imagejpeg($im2);
		//return $im2; 
			
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
