<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/23
 * Time: 23:26
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class TradeAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('tradeNo',null,['label'=>'订单编号'])
            ->add('goodsSnapshot.goodsImg',null,['label'=>'图片'])
            ->add('goodsSnapshot.goodsName',null,['label'=>'商品'])
            ->add('goodsSnapshot.goodsNum',null,['label'=>'数量'])
            ->add('totalAmount',null,['label'=>'总金额'])
            ->add('member.user.name',null,['label'=>'会员名称'])
            ->add('_action',null,[
                'actions'=>[
                    'show'=>[]
                ]
            ])
        ;
    }


    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }

    public function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {

    }

    public function configureRoutes(RouteCollection $collection)
    {
        if ($this->hasParentFieldDescription()) {
            $collection->remove('create');
        }
    }

}