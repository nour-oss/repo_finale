<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('formationId',ChoiceType::class, [
            'choices'  => [
                'PHP' => 4,
                'JAVA' => 2,
                'SQL' => 1,
                'SYMFONY' => 6,
                'CODENAME' => 5 ,
                'JQUERY' => 7,
                'JEE' => 11,
                'JAVASCRIPT' => 3,
            ],
        ])
            ->add('question')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
