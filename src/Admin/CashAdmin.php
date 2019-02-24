<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/23
 * Time: 23:21
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use App\Entity\Member;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CashAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('amount',null,['label'=>'金额'])
            ->add('member',EntityType::class,
                [
                    'class'=>Member::class,
                    'label'=>'会员',
                    'choice_label'=>'id'
                ])
            ->add('createdTime',null,['label'=>'申请时间'])
        ;

    }

    public function configureFormFields(FormMapper $form)
    {

    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }



}