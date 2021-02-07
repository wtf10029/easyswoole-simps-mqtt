<?php
namespace App\HttpController;

use App\Mqtt\Publish;
use EasySwoole\Http\AbstractInterface\Controller;

class Mqtt extends Controller
{

    /**
     * 向某个主题发布一条消息
     */
    public function publish()
    {

        try {
            $param   = $this->request()->getBody();
            $param   = json_decode($param, true);
            $publish = new Publish();
            $publish = $publish->publish($param);
        } catch (\Exception $e){
            return $this->writeJson($e->getCode(), $e->getMessage());
        }

        return $this->writeJson(20000, $publish);
    }

    /**
     * 向某个主题发布一条消息
     */
    public function publishCurl()
    {

        try {
            $param   = $this->request()->getBody();
            $param   = json_decode($param, true);
            $publish = new Publish();
            $publish = $publish->publishCurl($param);
        } catch (\Exception $e){
            return $this->writeJson($e->getCode(), $e->getMessage());
        }

        return $this->writeJson(20000, $publish);
    }

}