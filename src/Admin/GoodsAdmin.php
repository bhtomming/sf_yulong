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
use App\Entity\GoodsImage;
use App\Form\Type\GoodsImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class GoodsAdmin extends AbstractAdmin
{
    public function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('name',null,['label'=>'名称'])
            ->add('price',null,['label'=>'价格'])
        ;
    }

    public function configureFormFields(FormMapper $form)
    {
        $form->add('name',null,['label'=>'商品名称'])
            ->add('price',null,['label'=>'价格'])
            ->add('titleImg',null,['label'=>'标题图片'])
            ->add('sliderImages',CollectionType::class,[
                    'label'=>'上传图片',
                'entry_type' => GoodsImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,

            ])
            //->add('voide',FileType::class,['label'=>'视频'])
            ->add('stock',null,['label'=>'库存'])
            ->add('isFront',null,['label'=>'上首页'])
            ->add('hot',null,['label'=>'热销'])
            ->add('active',null,['label'=>'促销'])
            ->add('sorter',null,['label'=>'排序'])
            ->add('discountPrice',null,['label'=>'优惠价'])
            ->add('saling',null,['label'=>'是否上架'])
            ->add('pointExchange',null,['label'=>'是否可兑换'])
            ->add('summary',null,['label'=>'简介'])
            ->add('category',EntityType::class,[
                'class'=>Category::class,
                'label'=>'分类',
                'choice_label'=>'name'

            ])
            ->add('description',CKEditorType::class,['label'=>'详细描述','config'=>['toolbar'=>'full']])

        ;
    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name',null,['label'=>'商品名称'])
        ;
    }

    public function prePersist($goods)
    {
        assert($goods instanceof Goods);


        if($goods->getSaling()){
            $goods->setPublishTime(new \DateTime('now'));
        }

        return $goods;
    }

    public function preUpdate($goods)
    {
        assert($goods instanceof Goods);


    }


}