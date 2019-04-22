<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/23
 * Time: 23:23
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ExchangeAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('id',null,['label'=>'ID标识'])
            ->add('amount',null,['label'=>'充值金额'])
            ->add('status',null,['label'=>'状态'])
            ->add('member.userName',null,['label'=>'账号'])
            ->addIdentifier('createdTime','datetime',[
                'label'=>'充值时间',
                'format' => 'Y-m-d H:i:s'
            ])

        ;

    }



    public function configureShowFields(ShowMapper $show)
    {
        $show->add('amount')
            ->add('status')
            ->add('createdTime','datetime',[
                'label'=>'充值时间',
                'format' => 'Y-m-d H:i:s'
            ])
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

}