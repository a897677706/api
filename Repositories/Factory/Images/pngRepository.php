<?php
/**
* 
* @Author: jianghonggang
* 
*/

namespace App\Repositories\Factory\Images;
use App\Repositories\BaseRepository as Base;
use App\Repositories\Factory\Images\imageRepository;
class pngRepository implements imageRepository{
 	//private $list=[100,200,300,400,500,600,700,800,900,1000];
	public function __construct(){
		
	}


	public function resize_image($imageinfo,$data){
		$realpath=base_path().'/'.$imageinfo['url'];
		$im = imagecreatefrompng($realpath);
		$size=$this->getSize($data,$im);
		$im2 = imagecreatetruecolor($size['nx'], $size['ny']);
		foreach ($size['new'] as $key => $value) {
			imagecopyresized($im2, $im, 0, 0, 0, 0, floor($value['nw']), floor($value['ny']), $size['old']['x'], $size['old']['y']);
			Factory::getImageClass('redis')->getRedis()->lpush($data['imageId'].$size['sizeType'],$im2);
			if($key==0){
				$toshow=$im2;
			}
		}
		imagepng($toshow);
	}

	public function getSize($data,$im){
		$wc=strpos($data['size'],'w');
		$data['old']['x'] = imagesx($im);
		$data['old']['y'] = imagesy($im);
		for ($i=0; $i <10 ; $i++) { 

			if($wc===false) {
				$size=re_pleace('h','',$data['size'])+$i*10;
				$data['new'][$i]['nh'] = $size;
				$data['new'][$i]['nw'] = $data['y'] * $data['x']/$data['y'];
				$data['sizeType']='h';
			}
			else {
				$size=re_pleace('w','',$data['size'])+$i*10;
				$data['new'][$i]['nw'] = $size;
				$data['new'][$i]['nh'] = $data['y'] / $data['x'] * $data['w'] ;
				$data['sizeType']='w';
			}
		}

		return $data;

	}
	

}
