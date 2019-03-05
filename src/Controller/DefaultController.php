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
        $em = $this->getDoctrine()->getManager();

        $categoryHotel = $em->getRepository(Category::class)->find(1);
        $categoryHouse = $em->getRepository(Category::class)->find(2);
        $categoryTenycal = $em->getRepository(Category::class)->find(3);
        $categoryCuntry = $em->getRepository(Category::class)->find(4);

        $hotels = $categoryHotel->getGoods();
        $housies = $categoryHouse->getGoods();
        $tenycals = $categoryTenycal->getGoods();
        $contries = $categoryCuntry->getGoods();


        return $this->render('default/index.html.twig');
    }


    /**
     * @Route("/hot", name="hot_sale")
     */
    public function hotSales()
    {
        $em = $this->getDoctrine()->getManager();
        $goodses = $em->getRepository(Goods::class)->findBy([
            'sale'=> 'DESC',
            'order'=> 'DESC',
        ]);
        return $this->render('default/hot.html.twig');
    }


    /**
     * @Route("/list/{id}", name="goods_list")
     * @ParamConverter("category", options={"mapping"={"id"="id"}})
     */
    public function goodsList(Category $category)
    {
        return $this->render("default/category.html.twig",['category' => $category]);
    }


    /**
     * @Route("/goods/{id}", name="goods_show")
     * @ParamConverter("goods", options={"mapping"={"id"="id"}})
     */
    public function goodsShow(Goods $goods)
    {
        return $this->render("default/show.html.twig",['goods' => $goods]);
    }


    /**
     * @Route("/cart/add/{goods_id}/{num}", name="add_cart")
     * @ParamConverter("goods", options={"mapping"={"id"="goods_id"}})
     */
    public function addCart($goods, $num)
    {
        return $this->render("default/cart.html.twig");
    }

    /**
     * @Route("/recommend/", name="recommend")
     */
    public function recommend()
    {
        return $this->render("default/recommend.html.twig");
    }

}