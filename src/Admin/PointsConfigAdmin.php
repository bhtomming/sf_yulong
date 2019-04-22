<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/24
 * Time: 22:57
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PointsConfigAdmin extends AbstractAdmin
{


    public function configureFormFields(FormMapper $form)
    {
        $form->add('sharePoint',null,['label' => '推荐得积分'])
            ->add('derectPoint',null,['label' => '直接下线下单获得积分比例'])
            ->add('inderectPoint',null,['label' => '间接下线下单获得积分比例'])
            ->add('finderectPoint',null,['label' => '三级下线下单获得积分比例'])
            ->add('payPoint',null,['label' => '会员下单自己获得积分比例'])
            ->add('givePoint',null,['label' => '充值积分比例'])
            ->add('exchangePoint',null,['label' => '积分兑换比例'])
            ->add('exCondition',null,['label' => '积分提现条件'])
            ->add('teamCondition',null,['label' => '合伙人条件'])
            ->add('mbInterest',null,['label' => '会员利息'])
            ->add('teamInterest',null,['label' => '合伙人利息'])
        ;
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('delete');
    }

    public function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if ($this->hasAccess('edit')) {
            $menu->addChild($this->trans('修改'), [
                'uri' => $this->generateUrl('edit', ['id' => 1])
            ]);
        }
    }

}