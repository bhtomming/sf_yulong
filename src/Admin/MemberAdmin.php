<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/23
 * Time: 23:24
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MemberAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('id',null,['label'=>'会员ID'])
            ->add('amount',null,['label'=>'余额'])
            ->add('points',null,['label'=>'积分'])
            ->add('user',EntityType::class,[
                'label'=>'积分',
                'class'=>User::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureFormFields(FormMapper $form)
    {

    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }

}