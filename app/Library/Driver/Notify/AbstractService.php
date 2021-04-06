<?php

namespace Driver\Notify;

abstract class AbstractService
{
	protected $config; //消息配置
	protected $message;
	protected $code;

	public function setConfig($config)
	{
		$this->config = $config;
		return $this;
	}

	protected function error(int $code, string $message = null)
	{
		$this->code = $code;
		$this->message = $message;
		throw new NotifyException($code, $message);
	}

	public function _notify()
	{
		return ($this->code == 0) ? true : false;
	}

	/* 发送接口 */
	abstract public function send();
	/* 批量发送接口 */
	abstract public function sendBatch();
	/* 同步通知接口 */
	abstract public function _return();
	/* 异步通知接口 */
	// abstract public function _notify();
}
