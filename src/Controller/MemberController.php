<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/27
 * Time: 23:32
 * Site: http://www.drupai.com
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MemberController
 * @package App\Controller
 * @Route("/member", name="member_center")
 */

class MemberController extends AbstractController
{

    /**
     * @Route("/", name="member_index")
     */
    public function index()
    {

    }

    /**
     * @Route("/salelink", name="member_sale_link")
     */
    public function saleLink()
    {

    }

    /**
     * @Route("/exchange", name="member_exchange")
     */
    public function exchange()
    {

    }


    /**
     * @Route("/cash", name="member_cash")
     */
    public function cash()
    {

    }

}