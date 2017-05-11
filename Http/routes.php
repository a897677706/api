<?php



Route::group(['prefix'=>'/','middleware'=>['imageCache']], function(){
  
  Route::get('v1/img/{imageId}-{size}.{type}','Api\ImagesController@GetImage');
  

});