<?php
/**
 * 首先应该是订阅，订阅成功之后才能收到对应主题的发布消息
 * 文档：https://mqtt.simps.io
 */
namespace App\Mqtt;

use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Config;
use Simps\MQTT\Client;
use Simps\MQTT\Config\ClientConfig;
use Simps\MQTT\Hex\ReasonCode;
use Simps\MQTT\Protocol\Types;

class Subscribe extends AbstractProcess
{

    public function run($arg)
    {

        $getInstance              = Config::getInstance();
        $getConf                  = $getInstance->getConf('clientMqtt');
        $clientConfig             = $getConf['clientConfig'];
        $clientConfig['clientId'] = Client::genClientID();
        $configObj                = new ClientConfig($clientConfig);
        $client                   = new Client($getConf['host'], $getConf['port'], $configObj);

        while (!$connect = $client->connect()) {
            $client->connect();
        }

        $topics = [
            // 主题 => Qos
            'personnelPositioning/+' => 0,
        ];

        $time = time();
        $res  = $client->subscribe($topics);

        while (true) {
            $buffer = $client->recv(); // 收到的数据包
            echo "\nrecv\n" . json_encode($buffer, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            if ($buffer && $buffer !== true) {
                $time = time();
            }
            if ($time < (time() - $clientConfig['keepAlive'])) {
                $buffer = $client->ping();
                if ($buffer) {
                    echo 'send ping success' . PHP_EOL;
                    $time = time();
                } else {
                    $client->close();
                    break;
                }
            }
            // QoS1 发布回复
            if (isset($buffer['type']) && $buffer['type'] === Types::PUBLISH && isset($buffer['qos']) && $buffer['qos'] === 1) {
                $client->send([
                    'type'       => Types::PUBACK,
                    'message_id' => $buffer['message_id'] ?? '',
                    'code'       => ReasonCode::SUCCESS
                ]);
            }
        }
    }

}
