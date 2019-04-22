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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CashAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('amount',null,['label'=>'金额'])
            ->add('status',null,['label' => '状态'])
            ->add('member.userName',null, ['label'=>'会员',])
            ->addIdentifier('createdTime','datetime',['label'=>'申请时间','format' => 'Y-m-d H:i:s'])
            ->add('_action',null,[
                'actions'=>[
                    'show'=>[]
                ]
            ])
        ;

    }

    public function configureFormFields(FormMapper $form)
    {
        $form->add('amount',null,['label'=>'金额'])
            ->add('member',EntityType::class, [
                'label'=>'会员',
                'data_class'=>'App\Entity\Member',
                'class' => Member::class,
                'choice_label'=>'memberName'
                ])
            ->add('checkContent',TextareaType::class, ['label'=>'审核内容',])
            ->add('createdTime',null,['label'=>'申请时间','disabled'=>true])
        ;
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }



}