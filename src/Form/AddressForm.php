<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/15
 * Time: 12:35
 * Site: http://www.drupai.com
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddressForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullAddress',null,[
            'label' => '详细地址'
        ])
            ->add('zipCode',null,[
                'label' => '邮编'
            ])
            ->add('addressee',null,[
                'label'=>'收件人'
            ])
            ->add('phone',null,[
                'label' => '电话'
            ]);
    }

}