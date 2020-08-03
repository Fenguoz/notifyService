<?php

namespace Driver\Notify;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;

class NotifyFactory
{
	public function __construct()
	{
	}
	
	/**
	 * 构造适配器
	 * @param  $adapter_name 模块code
	 * @param  $adapter_config 模块配置
	 */
	public function set_adapter(string $adapter_name, array $adapter_data = [])
	{
		if (empty($adapter_name))
			throw new BusinessException(ErrorCode::ADAPTER_EMPTY);

		if (\App\Model\Notify::getStatusByCode($adapter_name) == 0)
			throw new BusinessException(ErrorCode::ADAPTER_TURN_OFF);

		$class_file = BASE_PATH . '/app/Library/Driver/Notify/' . $adapter_name . '/Service.php';
		if (!file_exists($class_file))
			throw new BusinessException(ErrorCode::FILE_NOT_FOUND);

		$class = "\Driver\Notify\\$adapter_name\Service";
		$this->adapter_instance = new $class($adapter_data);
		return $this->adapter_instance;
	}

	public function __call($method_name, $method_args)
	{
		if (method_exists($this, $method_name))
			return call_user_func_array(array(&$this, $method_name), $method_args);
		elseif (
			!empty($this->adapter_instance)
			&& ($this->adapter_instance instanceof AbstractService)
			&& method_exists($this->adapter_instance, $method_name)
		)
			return call_user_func_array(array(&$this->adapter_instance, $method_name), $method_args);
	}
}