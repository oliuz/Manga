<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MangaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', null, array(
              'disabled' => true
          ))
          ->add('status', null, array())
          ->add('genre', null, array())
          ->add('synopsis', null, array(
              'attr' => array('rows' => 7)
          ))
          ->add('author', null, array())
          ->add('yearStart', null, array())
          ->add('yearEnd', null, array())
          ->add('state', null, array())
          ->add('opinion', null, array())
          ->add('submit', SubmitType::class, array(
              'attr' => array('class' => 'btn-primary')
          ))
          /*->add('reset', ResetType::class, array(
              'attr' => array('class' => 'btn-danger')
          ))*/
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Manga'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_manga';
    }


}
