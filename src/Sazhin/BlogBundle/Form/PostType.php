<?php

namespace Sazhin\BlogBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                "label"=>"form.title",
                "required"=>true,
                'translation_domain' => 'messages',
            ])
            ->add('description',null,[
                "label"=>"form.description",
                "required"=>true,
                'translation_domain' => 'messages',
            ])
            ->add('content',null,[
                "label"=>"form.content",
                "required"=>true,
                'translation_domain' => 'messages',
            ])
            ->add('image', FileType::class, [
                'label' => 'form.image',
                'required'=>false
            ])
            ->add('categories', EntityType::class, [
                'class' => 'SazhinBlogBundle:Category',
                'choice_label' => 'name',
                'multiple' => true,
                "label"=>"form.categories",
                'translation_domain' => 'messages',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sazhin\BlogBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sazhin_blogbundle_post';
    }


}
