<?php
/**
* 
* @Author: jianghonggang
* 
*/

namespace App\Repositories\Factory\Images;

interface imageRepository {
 

	public function resize_image($imageinfo,$data);
	public function getSize($data,$im);
	

}
