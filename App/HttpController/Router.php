<?php
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {

        $routeCollector->addGroup('/mqtt',function (RouteCollector $collector){
            $collector->post('/publish', 'Mqtt/publish');         // 向某个主题发布一条消息
            $collector->post('/publishCurl', 'Mqtt/publishCurl'); // 向某个主题发布一条消息
        });

    }
}