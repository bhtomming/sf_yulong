<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/24
 * Time: 22:57
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PointsConfigAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {

    }

    public function configureFormFields(FormMapper $form)
    {
        $form->add('sharePoint',null,['label' => '推荐得积分'])
            ->add('derectPoint',null,['label' => '推荐得积分'])
            ->add('inderectPoint',null,['label' => '推荐得积分'])
            ->add('finderectPoint',null,['label' => '推荐得积分'])
            ->add('payPoint',null,['label' => '已分配积分'])
            ->add('givePoint',null,['label' => '积分利息'])
            ->add('exchangePoint',null,['label' => '积分兑换比例'])
            ->add('exCondition',null,['label' => '积分提现条件'])
            ->add('teamCondition',null,['label' => '合伙人条件'])
            ->add('mbInterest',null,['label' => '会员利息'])
            ->add('teamInterest',null,['label' => '合伙人利息'])
        ;
    }

}