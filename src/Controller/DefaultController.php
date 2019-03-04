<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/27
 * Time: 22:58
 * Site: http://www.drupai.com
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Goods;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends AbstractController
{
    /**
     * @Route("/",name="home_page")
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }


    /**
     * @Route("/hot", name="hot_sale")
     */
    public function hotSales()
    {
        return $this->render('default/hot.html.twig');
    }


    /**
     * @Route("/list/{id}", name="goods_list")
     * @ParamConverter("category", options={"mapping"={"id"="id"}})
     */
    public function goodsList(Category $category)
    {

    }


    /**
     * @Route("/goods/{id}", name="goods_show")
     * @ParamConverter("goods", options={"mapping"={"id"="id"}})
     */
    public function goodsShow(Goods $goods)
    {

    }


    /**
     * @Route("/cart/add/", name="add_cart")
     */
    public function addCart()
    {

    }

    /**
     * @Route("/recommend/", name="recommend")
     */
    public function recommend()
    {

    }

}