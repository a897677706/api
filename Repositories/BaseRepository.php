<?php
/**
 * @Desc: 业务逻辑基类
 * @Author: jianghonggang
 * 
 */
namespace App\Repositories;

class BaseRepository
{

	private $__errorMsg ;
	private $__errorStatus = false;
	private $__errorCode = '';

	public function getErrorMsg(){
		return $this->__errorMsg;
	}

	protected function setErrorMsg($msg,$code = ''){
		$this->__errorMsg = $msg;
		$this->__errorStatus = true;
		$this->__errorCode = $code;
	}

	public function isError(){
		return $this->__errorStatus;
	}

	public function getErrorCode(){
		return $this->__errorCode;
	}

}
