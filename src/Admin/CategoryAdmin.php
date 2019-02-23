<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/23
 * Time: 22:14
 * Site: http://www.drupai.com
 */

namespace App\Admin;


use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->add('name',null,['label'=>'名称'])
            ->add('description',null,['label'=>'描述'])
        ;
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {

    }

    public function configureFormFields(FormMapper $form)
    {
        $form->add('name',null,['label'=>'名称'])
            ->add('description',null,['label'=>'描述'])
            ->add('parent',EntityType::class,[
                'class'=>Category::class,
                'label'=>'上级分类',
                'choice_label'=>'name'
            ])
        ;
    }

}