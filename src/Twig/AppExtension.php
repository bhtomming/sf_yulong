<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/2
 * Time: 10:01
 * Site: http://www.drupai.com
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter("object_sort",[$this,'objectSort'])
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

}