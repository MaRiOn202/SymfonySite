<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),     // pour ne pas afficher l'id - générer auto
            TextField::new('title'),
            TextEditorField::new('content'),      //modifier description des articles - champ
            ImageField::new('image')->setUploadDir("/public/assets/blog/images/category")    //indique où l'on stocke l'image
                                                ->setBasePath("/assets/blog/images/category")                 //indique le dossier/le chemin de l'image à partir de la racine sur ordi - emplacement où l'on va chercher l'image
                                                ->setRequired(false),                            //pb quand on edit (change un élément de l'article) nous redemande l'img alors qu'L est déjà indiquée / champ à remplir obligatoirement - ici false
            AssociationField::new('author'),     //associe champ auteur avec les valeurs de la table user
            AssociationField::new('category'),
        ];
    }

}
