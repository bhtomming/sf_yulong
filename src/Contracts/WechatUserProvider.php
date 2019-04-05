<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/5
 * Time: 22:41
 * Site: http://www.drupai.com
 */

namespace App\Contracts;


interface WechatUserProvider
{
    public function find($openid);
}