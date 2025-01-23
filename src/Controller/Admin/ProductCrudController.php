<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            // ->setDateFormat('...')
            // ...
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('name')->setLabel('Nom')->setHelp('Nom de votre produit'),
            SlugField::new('slug')->setLabel('URL')->setTargetFieldName('name')->setHelp('URL de votre produit générée automatiquement'),
            TextEditorField::new('description')->setLabel('Description')->setHelp('Description de votre produit'),
            ImageField::new('image')->setLabel('Image')->setHelp('Image de votre produit en 600*600px')->setUploadDir('/public/uploads')->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].extension]')->setBasePath('/uploads'),
            NumberField::new('price')->setLabel('Prix H.T')->setHelp('Prix H.T du produit sans le sigle Euro'),
            ChoiceField::new('tva')->setLabel('Taux de TVA')->setChoices([
                '5.5%' => '5.5',
                '10%' => '10',
                '20%' => '20'
            ]),
            AssociationField::new('category', 'Catégories associées')
        ];
    }
}