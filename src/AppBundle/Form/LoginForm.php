<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 05/07/18
 * Time: 19:41
 */

namespace App\AppBundle\Form;


use PhpParser\Node\Expr\Empty_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username')
            ->add('_password', PasswordType::class)
        ;
    }

    public function getBlockPrefix()
    {
        return "";
    }
}