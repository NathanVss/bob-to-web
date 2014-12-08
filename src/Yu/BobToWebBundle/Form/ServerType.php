<?php

namespace Yu\BobToWebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('channelsNb', 'integer')
            ->add('Save', 'submit')
            ->add('Delete', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yu\BobToWebBundle\Entity\Server'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yu_bobtowebbundle_server';
    }
}
