<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/16
 * Time: 23:18
 * Site: http://www.drupai.com
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CashForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount',null,['label' => '金额']);
    }
}