<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/2
 * Time: 10:01
 * Site: http://www.drupai.com
 */

namespace App\Twig;


use App\Entity\PointsConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return [
            new TwigFilter("object_sort",[$this,'objectSort'])
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction("give_point",[$this,'givePoint'])
        ];
    }

    public function objectSort($arr,$property = "id",$op = "ASC")
    {
        //$tmp = null;
        for($i = 0; $i< count($arr); $i++)
        {
            $object = $arr[$i];
            if($i == count($arr) - 1)
            {
                break;
            }
            $object1 = $arr[$i + 1];
            $pro = "get".ucwords($property);
            switch ($op)
            {
                case "ASC":
                    if($object->$pro() > $object1->$pro())
                    {
                        $arr[$i] = $object1;
                        $arr[$i + 1] = $object;
                    }
                    break;
                case "DESC":
                    if($object->$pro() < $object1->$pro())
                    {
                        $arr[$i] = $object1;
                        $arr[$i + 1] = $object;
                    }
                    break;
                default:
                    break;
            }

        }
        return $arr;
    }

    public function givePoint($price)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $pointsConfig = $em->getRepository(PointsConfig::class)->find(1);
        $givePoints = 0;
        if($pointsConfig instanceof PointsConfig){
            $givePoints = $pointsConfig->getPayPoint() * $price /100;
        }
        return $givePoints;
    }

}