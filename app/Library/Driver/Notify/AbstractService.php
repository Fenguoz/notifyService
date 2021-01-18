<?php

namespace Driver\Notify;

use App\Model\Queue;

abstract class AbstractService
{
	protected $config; //消息配置
	protected $template; //模版信息
	protected $param; //参数
	protected $templateValue; //模版值 #code# => 123456
	protected $message;
	protected $code;
	protected $account;

	public function setConfig($config)
	{
		$this->config = $config;
		return $this;
	}

	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}

	public function templateValue()
	{
		if ($this->template->param) {
			$template_param = json_decode($this->template->param, true);
			foreach ($template_param as $replace_str => $variable) {
				if (isset($this->param[$variable])) $this->templateValue[$replace_str] = $this->param[$variable];
			}
		}
		return $this;
	}

	public function batchTemplateValue()
	{
		if ($this->template->param) {
			$template_param = json_decode($this->template->param, true);
			foreach ($this->param as $account => $param) {
				$item = [];
				foreach ($template_param as $replace_str => $variable) {
					if (isset($param[$variable])) {
						$this->account[] = $account;
						$item[$replace_str] = $param[$variable];
					}
				}
				if ($item) $this->templateValue[] = $item;
			}
		}
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
		if (isset($this->config['id'])) {
			Queue::where('id', $this->config['id'])->update([
				'status' => ($this->code == 0) ? 1 : -1
			]);
		}
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
