<?php

namespace App\Controller;

use App\Entity\Article;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;


class HomeController extends Controller
{
    /**
     * @Route("/index/{page}", name="index")
     * @Route("/")
     */
    public function index($page=1)
    {
        $nbNews=5;


        try{
            $articles= $this->getDoctrine()
                ->getRepository(Article::class)
                ->findByPage($page);

            $maxPage = (int) $this->getDoctrine()
                ->getRepository(Article::class)
                ->getNumber();
        }
        catch(\Exception $e){
            return $this->redirectToRoute('index');
        }


        $maxPage = ceil($maxPage/$nbNews);


        return $this->render('index.html.twig', array(
            'articles' => $articles,
            'page' => $page,
            'maxPage'=>$maxPage
        ));
    }


    public function searchBarAction(){

        $form = $this->createFormBuilder(null)
            ->add('search',TextType::class)
            ->getForm();



        return $this->render('article/search_form.html.twig', array(
            'form'=> $form->createView()
        ));

    }

    /**
     * @Route("/search",name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(Request $request) {


        $search = $request->request->get('form');

        $search=$search['search'];

        $articles=$this->getDoctrine()
            ->getRepository(Article::class)
            ->findByTitle($search);

        return $this->render('index.html.twig', array(
            'articles' => $articles,
            'page' => -1
        ));

    }

}