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

class TradeAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('tradeNo',null,['label'=>'订单编号'])
            ->add('totalAmount',null,['label'=>'总金额'])
        ;
    }

    public function configureFormFields(FormMapper $form)
    {

    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }

    public function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {

    }

}