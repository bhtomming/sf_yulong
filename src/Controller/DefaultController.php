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
use App\Entity\GoodsSnapshot;
use App\Entity\Trade;
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

        $hts = $categoryHotel->getGoodsBySort();


        return $this->render('default/index.html.twig',['hts' => $hts]);
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

    /**
     * @Route("/order/add", name="add_order")
     * 下订单,生成订单
     */
    public function addOrder()
    {
        $user = $this->getUser();
        if(!($user instanceof User)){
            return $this->createAccessDeniedException(['你无权访问']);
        }
        $member = $user->getMember();
        $carts = $member->getCart();
        $trade = new Trade();
        foreach ($carts as $cart){
            $goodsSnapshot = new GoodsSnapshot();
            $goods = $cart->getGoods();
            $goodsSnapshot->setGoodsId($goods->getId());
            $goodsSnapshot->setGoodsName($goods->getName());
            $goodsSnapshot->getGoodsImg($goods->getGoodsImg);
            $goodsSnapshot->setGoodsNum($cart->getNum());
            $goodsSnapshot->setGoodsPrice($goods->getPrice());
            $goodsSnapshot->setGoodsLink("goods/show/".$goods->getId());
        }


    }

}