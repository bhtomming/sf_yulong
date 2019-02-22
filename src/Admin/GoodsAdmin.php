<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/22
 * Time: 22:45
 * Site: http://www.drupai.com
 */

namespace App\Admin;




use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class GoodsAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('name',null,['label'=>'名称'])
            ->add('price',null,['label'=>'价格'])
        ;
    }

    public function configureFormFields(FormMapper $form)
    {

    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }


}