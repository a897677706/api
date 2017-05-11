<?php
/**
* 
* @Author: jianghonggang
* 
*/

namespace App\Repositories\cache;
use App\Repositories\BaseRepository as Base;



class redisRepository{
 
	public function __construct(){
		
	}

	public function getRedis(){
		$redis=newRedis();
		$redis->connect("127.0.0.1",6379);
		return $redis;
	}
	

}
