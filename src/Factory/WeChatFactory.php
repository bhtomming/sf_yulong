<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/3/14
 * Time: 17:54
 * Site: http://www.drupai.com
 */

namespace App\Factory;



use App\Entity\WechatConfig;
use App\Servers\WeChatServer;
use Doctrine\ORM\EntityManagerInterface;
use EasyWeChat\OfficialAccount\Application;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class WeChatFactory
{
    /**
     * @param $config
     * @param $logger
     * @param $cache
     * @return Application
     */
    public static function createNewInstance(WeChatServer $server, LoggerInterface $logger, $cache = null)
    {
        $config = [];
        if ($cache) {
            $config['cache'] = $cache;
        }
        /*$weconfig = $em->getRepository(WechatConfig::class)->find(1);
        if($weconfig instanceof WechatConfig){
            $config = [
                'app_id' => $weconfig->getAppid(),
                'appscret' => $weconfig->getAppscret(),
                'token' => $weconfig->getToken(),
                'access_token' => $weconfig->getAccessToken(),
            ];
        }
        $application = new Application($config);
        */


        $application = $server->getApp();


        return $application;
    }

}