<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Repository\MangaRepository;

class SearchType extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    /** @var \Doctrine\ORM\EntityManager $em */
    $em = $options['entity_manager'];
    /* @var $rep \AppBundle\Repository\MangaRepository */
    $rep = $em->getRepository("AppBundle:Manga");
    
    $builder
      ->add('name', TextType::class, array(
        'label'    => false,
        'required' => false
      ))
      ->add('status', ChoiceType::class, array(
        'choices' => $rep->getDistinctStatus(),
        'label'    => false,
        'placeholder' => '',
        'empty_data'  => null,
        'required' => false
      ))
      ->add('genre', ChoiceType::class, array(
        'choices' => $rep->getDistinctGenre(),
        'label'    => false,
        'placeholder' => '',
        'empty_data'  => null,
        'required' => false
      ))
      ->add('author', TextType::class, array(
        'label'    => false,
        'required' => false
      ))
      ->add('synopsis', TextType::class, array(
        'label'    => false,
        'required' => false
      ))
      //->add('yearStart', null, array())
      //->add('yearEnd', null, array())
      ->add('state', ChoiceType::class, array(
        'choices' => $rep->getDistinctState(),
        'label'    => false,
        'placeholder' => '',
        'empty_data'  => null,
        'required' => false
      ))
      ->add('opinion', TextType::class, array(
        'label'    => false,
        'required' => false
      ))
      ->add('url', TextType::class, array(
        'label'    => false,
        'required' => false
      ))
      ->add('search', SubmitType::class, array(
        'attr' => array('class' => 'btn-primary')
      ))
      ->add('reset', SubmitType::class, array(
        'attr' => array('class' => 'btn-danger')
      ))
    ;
  }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setRequired('entity_manager');
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
