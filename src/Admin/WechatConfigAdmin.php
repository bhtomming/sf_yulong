<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/23
 * Time: 23:26
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class WechatConfigAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('appid',null,['label'=>'appid'])
            ->add('appscret',null,['label'=>'appscret'])
            ->add('token',null,['label'=>'token'])
            ->add('reply',null,['label'=>'关注回复'])
            ;
    }

    public function configureFormFields(FormMapper $form)
    {
        $form->add('appid',null,['label'=>'appid'])
            ->add('appscret',null,['label'=>'appscret'])
            ->add('token',null,['label'=>'token'])
            ->add('reply',null,['label'=>'关注回复'])
        ;

    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }

}