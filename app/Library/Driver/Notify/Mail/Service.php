<?php

namespace Driver\Notify\Mail;

use Driver\Notify\AbstractService;
use PHPMailer\PHPMailer\PHPMailer;

class Service extends AbstractService
{
    protected $emails;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function send()
    {
        $channel = new \Swoole\Coroutine\Channel();
        co(function () use ($channel) {
            $mail = new PHPMailer; //PHPMailer对象
            $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
            $mail->IsSMTP(); // 设定使用SMTP服务
            $mail->SMTPDebug = 0; // 关闭SMTP调试功能
            $mail->SMTPAuth = true; // 启用 SMTP 验证功能
            $mail->SMTPSecure = 'ssl'; // 使用安全协议
            $mail->Host = $this->config['host']; // SMTP 服务器
            $mail->Port = $this->config['port']; // SMTP服务器的端口号
            $mail->Username = $this->config['username']; // SMTP服务器用户名
            $mail->Password = $this->config['password']; // SMTP服务器密码
            $mail->SetFrom($this->config['send_mail'], $this->config['send_nickname']); // 邮箱，昵称
            $mail->Subject = $this->template->title;
            $mail->MsgHTML($this->replaceTemplate());
            $mail->AddAddress($this->param['address']); // 收件人
            if ($this->config['attachment']) {
                $mail->addAttachment(BASE_PATH . $this->config['attachment']);
            }
            $result = $mail->Send();
            if ($result) {
                var_dump('ok');
            } else {
                $result = $error = $mail->ErrorInfo;
                var_dump($result);
            }
            $channel->push($result);
        });
        $data = $channel->pop();
        return $this->_notify();
    }

    public function sendBatch()
    {
    }

    public function _return()
    {
    }

    public function replaceTemplate()
    {
        $content = $this->template->content;
        if ($content && $this->templateValue) {
            $key = [];
            $value = [];
            foreach ($this->templateValue as $k => $v) {
                $key[] = $k;
                $value[] = $v;
            }
            $content = str_replace($key, $value, $content);
        }
        return $content;
    }
}
