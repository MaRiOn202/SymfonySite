<?php

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $repoArticle;
    private $repoCategory;                              //attribut de classe

    public function __construct(ArticleRepository $repoArticle, CategoryRepository $repoCategory)        // si on a bcp d'actions qui ont besoin de repository, l'initialiser en fonction dans un constructeur - permet de dire à Sym de le faire systématiqnt
        {
            $this->repoArticle = $repoArticle;
            $this->repoCategory = $repoCategory;                 //initialisation de repoArticle
        }


    #[Route('/home', name: 'home')]
    public function index(): Response                                 //la méthode index est dépendante du repository, placée ici en tant que paramètre = injection de dépendance
    {
        $categories = $this->repoCategory->findAll();
        //dd($categories);                                                vérifier que cela fonctionne bien - les données sont bien présentes
        $articles = $this->repoArticle->findAll();
        return $this->render("home/index.html.twig",[
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    #[Route('/show/{id}', name: 'show')]
    public function show(Article $article, Category $category): Response
    {
        //dump($article);
   /*     if (!$article) {
            return $this->redirectToRoute('home');
        }*/

        return $this->render("show/index.html.twig",[
            'article' => $article,
        ]);
    }

    #[Route('/showArticle/{id}', name: 'show_article')]
    public function showArticle(?Category $category): Response
    {
        if ($category) {
            $articles = $category->getArticles()->getValues();                       // getArticles= on récupere la collection -array / getValues = données -valeurs plutot
        } else {
            return $this->redirectToRoute('home');
        }

       //dd($articles);

        $categories = $this->repoCategory->findAll();
        return $this->render("home/index.html.twig",[
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

}
