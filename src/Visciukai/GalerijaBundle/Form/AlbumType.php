<?php

namespace Visciukai\GalerijaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlbumType extends AbstractType
{
    /**
     * @var bool
     */
    private $isAdmin;

    public function __construct($isAdmin = false)
    {
        $this->isAdmin = $isAdmin;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null, ['label' => 'Pavadinimas', 'required' => true]);
        if($this->isAdmin) $builder->add('user', null, ['label' => 'Redagavimo teise']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Visciukai\GalerijaBundle\Entity\Album'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'visciukai_galerijabundle_album';
    }
}
