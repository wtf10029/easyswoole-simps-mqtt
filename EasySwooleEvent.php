<?php
namespace EasySwoole\EasySwoole;

use App\Mqtt\Subscribe;
use EasySwoole\Component\Process\Manager;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\Component\Process\Config;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {

        //自定义进程mqtt
        $mqttConfig = new Config();
        $mqttConfig->setProcessName('mqtt');//设置进程名称
        $mqttConfig->setProcessGroup('subscribe');//设置进程组
        $mqttConfig->setArg([]);//传参
        $mqttConfig->setRedirectStdinStdout(false);//是否重定向标准io
        $mqttConfig->setPipeType($mqttConfig::PIPE_TYPE_SOCK_DGRAM);//设置管道类型
        $mqttConfig->setEnableCoroutine(true);//是否自动开启协程
        $mqttConfig->setMaxExitWaitTime(3);//最大退出等待时间

        Manager::getInstance()->addProcess(new Subscribe($mqttConfig));
    }
}