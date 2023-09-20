<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Nom'
            ])
            ->add('description')
            ->add('price', MoneyType::class, options: [
                'label' => 'Prix',
                'divisor' => 100,
                'constraints' => [
                    new Positive(message: "Le prix ne peut pas être négatif")
                ]
            ])
            ->add('stock', options: [
                'label' => 'Unités en stock',
                'constraints' => [
                    new Positive(message: "Le stock ne peut pas être négatif")
                ]
            ])
            // pour categories il faut présicer le type, 
            //ici c'est une entité (EntityType) 
            //et le nom de l'entité dans les options
            ->add('categories', EntityType::class, options: [
                'class' => Categories::class,
                //choisir le label
                'choice_label' => 'name',
                //le renomer
                'label' => 'Catégorie',
                //grouper les catégories par les parents
                'group_by' => 'parent.name',
                //faire une requête pour afficher uniquement les catégories 
                //qui ont des parents et les trier par ordre alphabétique
                'query_builder' => function (CategoriesRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->orderby('c.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
