<?php

namespace App\Form;

use App\Entity\ProductCategory;
use App\Entity\PropertySearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) // Création d'un formulaire
    {
        $builder
            ->add('maxPrice', IntegerType::class, [ // Recherche par prix max
                'required' => false,
                'label' => false,
                'attr' => ['placeholder' => 'Budget max']])
            ->add('selectedCategory', EntityType::class, [ // Recherche par catégorie
                'class' => ProductCategory::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Catégorie'])
            ->add('searchbar', SearchType::class, [ // Recherche
                'required' => false,
                'label' => false,
                'attr' => ['placeholder' => 'Recherche..']])
            ->add('orderBy1', ChoiceType::class, [ // Tri
                'choices' => [
                    'Prix croissant' => 1,
                    'Prix décroissant' => 2,
                    'Du plus ancien au plus récent' => 3,
                    'Du plus récent au plus ancien' => 4,
                    'Par meilleurs notes' => 5,],
                'placeholder' => "Trier les produits",
                'required' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
        ]);
    }
}
