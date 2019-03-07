<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WechatConfigRepository")
 */
class WechatConfig
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $appid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $access_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $appscret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $expiresIn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reply;

    public function getPayConfig(){
        return array(
            'app_id' => $this->appid,
            'appscret' => $this->appscret,
            'mch_id'=> '商户ID',
            'key' => '密钥',
            'notify_url' => '支付返回路径',
            'cert_client' => '退款用的证书',
            'cert_key' =>'退款用的证书',
            'log' => [ // optional
                'file' => './logs/wechat.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
                // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
            ],
            'mode' => 'dev', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppid(): ?string
    {
        return $this->appid;
    }

    public function setAppid(string $appid): self
    {
        $this->appid = $appid;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setAccessToken(?string $access_token): self
    {
        $this->access_token = $access_token;

        return $this;
    }

    public function getAppscret(): ?string
    {
        return $this->appscret;
    }

    public function setAppscret(?string $appscret): self
    {
        $this->appscret = $appscret;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiresIn(): ?string
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?string $expiresIn): self
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function getReply(): ?string
    {
        return $this->reply;
    }

    public function setReply(?string $reply): self
    {
        $this->reply = $reply;

        return $this;
    }
}
