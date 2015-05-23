<?php

namespace Visciukai\ImagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    /**
     * @var bool
     */
    private $createForm;

    public function __construct($createForm = true)
    {
        $this->createForm = $createForm;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Pavadinimas'])
            ->add('description', null, ['label' => 'Aprašymas']);

        if ($this->createForm) {
            $builder->add('file', null, ['label' => 'Failas']);
        } else {
            $builder->add('album', null, ['label' => 'Albumas']);
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Visciukai\ImagesBundle\Entity\Image'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'visciukai_imagesbundle_image';
    }
}
