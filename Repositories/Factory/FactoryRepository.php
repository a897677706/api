<?php
/**
* 
* @Author: jianghonggang
* 
*/

namespace App\Repositories\Factory;
use App\Repositories\BaseRepository as Base;
use App\Repositories\Factory\Images\jpgRepository;
use App\Repositories\Factory\Images\webpRepository;
use App\Repositories\Factory\Images\pngRepository;
use App\Repositories\cache\redisRepository;


class FactoryRepository extends Base{
 
	public function __construct(){
		
	}


	public static function getImageClass($data){
		$webp = strpos($_SERVER['HTTP_ACCEPT'], 'image/webp');

		if($webp===false){
			switch ($data) {
				case 'jpg':
					return new jpgRepository;
					break;
				case 'jpeg':
					return new jpgRepository;
					break;
				case 'png':
					return new pngRepository;
					break;
				default:
					# code...
					break;
			}
		}else{
			return new App\Repositories\Factory\Images\webpRepository;
		}
	}


	public static function getCacheClass($data){
		

		
			switch ($data) {
				case 'redis':
					$redis=new redisRepository();
					return $redis;
				default:
					# code...
					break;
			}

	}
	

}
