<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/6
 * Time: 22:31
 * Site: http://www.drupai.com
 */

namespace App\Events;


use App\Entity\WeChat;
use App\Event\AuthorizeEvent;
use App\Event\Events;
use App\Servers\WeChatServer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WechatEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * AuthenticationHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::AUTHORIZE => 'authorize',
        ];
    }

    /**
     * 处理微信用户授权
     * @param AuthorizeEvent $event
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function authorize(AuthorizeEvent $event)
    {

        $wx_user = $event->getUser();

        $manager = $this->container->get('doctrine.orm.default_entity_manager');
        $repository = $manager->getRepository('App:WeChat');

        $user = $repository->findOneBy(['openid' => $wx_user['openid']]);

        // 若无此用户则写入数据库
        if (!$user) {
            dump($user);exit;
            $weServer = new WeChatServer($manager);
            $weServer->register($wx_user['openid']);
            /*$user = new WeChat();
            $user->setOpenid($wx_user['openid']);
            $user->setNickname($wx_user['nickname']);
            $manager->persist($user);
            $manager->flush();*/
        }
    }
}