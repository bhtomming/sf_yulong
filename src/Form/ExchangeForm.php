<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/14
 * Time: 15:37
 * Site: http://www.drupai.com
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ExchangeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("amount",null,['label'=>'充值金额']);
    }

}