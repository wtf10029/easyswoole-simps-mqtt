<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9503,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time'=>3
        ],
        'TASK'=>[
            'workerNum'=>4,
            'maxRunningNum'=>128,
            'timeout'=>15
        ]
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    'clientMqtt' => [
        'clientConfig' => [
            'userName'      => '',     // 用户名
            'password'      => '',     // 密码
            'clientId'      => '',     // 客户端id
            'keepAlive'     => 50,     // 默认0秒，设置成0代表禁用
            'protocolName'  => 'MQTT', // 协议名，默认为MQTT(3.1.1版本)，也可为MQIsdp(3.1版本)
            'protocolLevel' => 4,      // 协议等级，MQTT3.1.1版本为4，5.0版本为5，MQIsdp为3
            'properties'    => [],     // MQTT5 中所需要的属性
            'delay'         => 3000,   // 重连时的延迟时间 (毫秒)
            'maxAttempts'   => 5,      // 最大重连次数。默认-1，表示不限制
            'swooleConfig'  => [
                'open_mqtt_protocol' => true,
                'package_max_length' => 2 * 1024 * 1024
            ]
        ],
        'host'      => '127.0.0.1',
        'port'      => 1883,
        'appID'     => 'admin',
        'appSecret' => 'public',
        'publishUrl' => 'http://127.0.0.1:8081/api/v4/mqtt/publish',
    ]
];
