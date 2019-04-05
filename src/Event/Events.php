<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/5
 * Time: 23:11
 * Site: http://www.drupai.com
 */

namespace App\Event;


final class Events
{

    const AUTHORIZE                              = 'lilocon.wechat.authorize';

    const OPEN_PLATFORM_AUTHORIZED        = 'lilocon.wechat.open_platform.authorized';
    const OPEN_PLATFORM_UPDATE_AUTHORIZED = 'lilocon.wechat.open_platform.update_authorized';
    const OPEN_PLATFORM_UNAUTHORIZED      = 'lilocon.wechat.open_platform.unauthorized';


    final private function __construct()
    {
    }
}