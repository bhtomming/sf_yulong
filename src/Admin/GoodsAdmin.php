<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/2/22
 * Time: 22:45
 * Site: http://www.drupai.com
 */

namespace App\Admin;




use App\Entity\Category;
use App\Entity\Goods;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
        $form->add('name',null,['label'=>'商品名称'])
            ->add('price',null,['label'=>'价格'])
            ->add('titleImg',null,['label'=>'标题图片'])
            ->add('stock',null,['label'=>'库存'])
            ->add('isFront',null,['label'=>'上首页'])
            ->add('hot',null,['label'=>'热销'])
            ->add('sorter',null,['label'=>'排序'])
            ->add('saling',null,['label'=>'是否上架'])
            ->add('summary',null,['label'=>'简介'])
            ->add('category',EntityType::class,[
                'class'=>Category::class,
                'label'=>'分类',
                'choice_label'=>'name'
            ])
            ->add('description',null,['label'=>'详细描述'])
        ;
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name',null,['label'=>'商品名称'])
        ;
    }

    public function create($goods)
    {
        if(! $goods instanceof Goods){
            return null;
        }
        if($goods->getSaling()){
            $goods->setPublishTime(new \DateTime('now'));
        }

        return parent::create($goods);
    }


}