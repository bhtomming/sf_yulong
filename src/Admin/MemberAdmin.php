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
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MemberAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('id',null,['label'=>'会员ID'])
            ->add('amount',null,['label'=>'余额'])
            ->add('points',null,['label'=>'积分'])
            ->add('user.name',null,[
                'label'=>'会员名称'
            ])
        ;
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }

}