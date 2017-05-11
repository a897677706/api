<?php
/*
* @Desc: 缓存中间件
* @Author: jianghongang
* 
*/
namespace App\Http\Middleware;

use Closure;
use PageCache;
use App\Repositories\Factory\FactoryRepository as Factory;

class imageCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

       
        $data=$request->all();
        $check=strpos($data['size'], 'w')===false?'h':'w';
        $imgList=Factory::getImageClass('redis')->getRedis()->hgetall($data['imageId'].$check);
        $data['size']=str_replace('w', '', $data['size']);
        $size=str_replace('h', '', $data['size']);
        if(!empty($imgList)){
            foreach ($imgList as $key => $value) {
                 $arr2[$key]=abs($size-$key);
            }
            $min= min($arr2);
            $showSize=array_search($min, $arr2); 
            $webp = strpos($_SERVER['HTTP_ACCEPT'], 'image/webp');
            if($webp===false){
                if($data['type']=='jpg'||$data['type']=='jpg'){
                    imagejpeg($imgList[$showSize]);
                }elseif($data['type']=='png'){
                    imagepng($imgList[$showSize]);
                }
            }else{
                imagewebp($imgList[$showSize]);
            }
        }
        
     


        return $next($request);
    }
}
