<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/3/27
 * Time: 23:55
 * Site: http://www.drupai.com
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MemberLoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,['label'=>'用户名'])
            ->add('password',PasswordType::class,['label'=>'密码'])
        ;
    }

}