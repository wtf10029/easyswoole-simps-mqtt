<?php
/**
 * 订阅成功之后，推送消息
 * 文档：https://mqtt.simps.io
 */
namespace App\Mqtt;

use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Config;
use Simps\MQTT\Client;
use Simps\MQTT\Config\ClientConfig;
use Simps\MQTT\Hex\ReasonCode;
use Simps\MQTT\Protocol\Types;

class Publish
{

    public $clientConfig;
    public $host;
    public $port;
    public $appID;
    public $appSecret;
    public $publishUrl;

    public function __construct()
    {
        $getInstance        = Config::getInstance();
        $getConf            = $getInstance->getConf('clientMqtt');
        $this->clientConfig = $getConf['clientConfig'];
        $this->host         = $getConf['host'];
        $this->port         = $getConf['port'];
        $this->appID        = $getConf['appID'];
        $this->appSecret    = $getConf['appSecret'];
        $this->publishUrl   = $getConf['publishUrl'];

        $this->clientConfig['clientId'] = Client::genClientID();
    }

    public function publish($param = [], $topic = 'personnelPositioning/test')
    {

        $clientConfig = new ClientConfig($this->clientConfig);
        $client       = new Client($this->host, $this->port, $clientConfig);

        while (!$connect = $client->connect()) {
            $client->connect();
        }

        $param   = json_encode($param, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $publish = $client->publish($topic, $param);

        return $publish;
    }

    public function publishCurl($param = [], $topic = 'personnelPositioning/test')
    {

        $authorization = base64_encode("{$this->appID}:{$this->appSecret}");

        $header = [
            "Content-type:application/json",
            "Accept:application/json",
            "Authorization:Basic $authorization"
        ];

        $data = [
            "topic"    => $topic,
            "payload"  => $param,
            "qos"      => 1,
            "retain"   => false,
            "clientid" => "example"
        ];

        $data = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $curl = curl_init();

        $options = [
            CURLOPT_URL            => $this->publishUrl,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_HTTPHEADER     => $header,
        ];

        curl_setopt_array($curl, $options);

        $curl_exec = curl_exec($curl);

        if($curl_exec != true) {
            $error = curl_error($curl);
            throw new \Exception("调用API时发生错误[$error]");
        }

        curl_close($curl);

        return $curl_exec;
    }

}
