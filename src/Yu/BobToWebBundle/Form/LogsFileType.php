<?php

namespace Yu\BobToWebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LogsFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', 'text')
            ->add('lastSize', 'text')
            ->add('lastCheckTime', 'text')
            ->add('Save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yu\BobToWebBundle\Entity\LogsFile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yu_bobtowebbundle_logsfile';
    }
}
