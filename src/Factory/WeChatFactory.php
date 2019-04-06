<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/3/14
 * Time: 17:54
 * Site: http://www.drupai.com
 */

namespace App\Factory;



use EasyWeChat\OfficialAccount\Application;
use Psr\Log\LoggerInterface;

class WeChatFactory
{
    /**
     * @param $config
     * @param $logger
     * @param $cache
     * @return Application
     */
    public static function createNewInstance(array $config, LoggerInterface $logger, $cache = null)
    {
        Log::setLogger($logger);

        if ($cache) {
            $config['cache'] = $cache;
        }

        $application = new Application($config);

        return $application;
    }

}