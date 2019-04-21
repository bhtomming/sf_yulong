<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/21
 * Time: 16:41
 * Site: http://www.drupai.com
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoodsImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('filename',TextType::class,[
            'attr'=>[
                'class'=>'col-xs-6'
            ],
            'required' => false,
            'label' => ' '
        ])
            ->add('file',FileType::class,[
                'attr'=>[
                    'class'=> 'col-xs-6'
                ],
                'required' => false,
                'label'=>' '
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class'=> 'App\Entity\GoodsImage'
            ]);
    }

    public function getBlockPrefix()
    {
        return 'goods_image';
    }

}