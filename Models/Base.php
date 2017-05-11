<?php
/**
* Model基类
* @Author: KongSeng
* @Email: 643828892@qq.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;

class Base extends Model
{
	protected $_pageSize = 15;

	/**
	* 将数组填充到Model对象属性
	* @param array $data 一维数组
	*/
	public function fillA2O($data){

		if( empty($data) ){
			return false;
		}

		foreach ($data as $key => $value) {
			if(is_array($value)) $value = serialize($value);
			$this->$key = trim($value);
		}

	}

	/**
	* 用And连接词合并筛选条件
	* @param array $filter
	* @param string $type
	* @return object
	*/
	public function mergeFilter($filter,$type = 'and'){
		$temp = $this->__getTempFilter($filter);
		$filterValue = $temp['filterValue'];
		$filterField = $temp['filterField'];
		if( !empty( $filterValue ) || !empty($filterField) ){
			$filterStr = implode(' '.$type.' ',$filterField);
			return $this->whereRaw($filterStr,$filterValue);
		}else{
			return $this;
		}
	}

	private function __getTempFilter($filter){
		$filterField = [];
		$filterValue = [];
		foreach ($filter as $key => $value) {
			if( $value === '' )continue;
			if( $key == 'page')continue;
			if( $value !== null || $value !== '' ){
				$temp = explode('|',$key);
				$field = $temp[0];
				$operation = trim(isset($temp[1])?$temp[1]:'=');
				switch ($operation) {
					case 'like':
						$filterField[] = $field.' like ?';
						$filterValue[] = '%'.$value.'%';
						break;
					case 'l_like':
						$filterField[] = $field.' like ?';
						$filterValue[] = '%'.$value;
						break;
					case 'r_like':
						$filterField[] = $field.' like ?';
						$filterValue[] = $value.'%';
						break;
					case 'in':
						if( is_array($value) ){
							foreach ($value as $key => $v) {
								$value[$key] = "'$v'";
							}
							$value = implode(',',$value);
						}
						$filterField[] = $field." in(".$value.")";
						break;
					case 'eq':
						$filterField[] = $field.' = ?';
						$filterValue[] = $value;
						break;
					case 'neq':
						$filterField[] = $field.' != ?';
						$filterValue[] = $value;
						break;
					case 'gthan':
						$filterField[] = $field.' > ?';
						$filterValue[] = $value;
						break;
					case 'lthan':
						$filterField[] = $field.' < ?';
						$filterValue[] = $value;
						break;
					case 'gthaneq':
						$filterField[] = $field.' >= ?';
						$filterValue[] = $value;
						break;
					case 'lthaneq':
						$filterField[] = $field.' <= ?';
						$filterValue[] = $value;
						break;
					default:
						$filterField[] = $field.' = ?';
						$filterValue[] = $value;
						break;
				}
			}
		}
		return [
			'filterField' => $filterField,
			'filterValue' => $filterValue,
		];
	}

	/**
	* 按分页获取数据列表
	* @param object 查询中间件
	* @return false/array
	*/
	public function getByPage($mid = null){
		 if( ! $mid ){
			 $mid = $this;
			 $mid = $mid->orderBy('updated_at','desc');
		 }
		 $_pageSize = $this->_pageSize ? $this->_pageSize : 15;
		 $res = $mid->paginate($_pageSize)->toArray();

		 if( ! empty($res) ){
			 $data = $res['data'];
			 unset($res['data']);
			 $page = $res;
			 $uri = explode('?',Request::getRequestUri());
			 $paramStr = '';
			 if( count($uri) > 1 ){
				 $paramStr = trim(preg_replace('/page=[\d]*/is','',$uri[1]));
			 }

			 if( $paramStr != ''  && substr($paramStr,0,1) != '&' ){
				 $paramStr = '&'.$paramStr;
			 }

			 if( $page['next_page_url'] ){
				 $page['next_page_url'] .= $paramStr;
			 }

			 if( $page['prev_page_url'] ){
				 $page['prev_page_url'] .= $paramStr;
			 }

			 $page['last_page_url'] = $uri[0].'?page='.$page['last_page'].$paramStr;
			 $page['first_page_url'] = $uri[0].'?page=1'.$paramStr;

			 return ['data'=>$data,'page'=>$page];
		 }
		 return false;
	}

	public function setPageSize($num){
		if( !$num ) return false;
		$this->_pageSize = $num;
	}
}
