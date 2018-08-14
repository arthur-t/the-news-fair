<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 07/07/18
 * Time: 16:34
 */

namespace App\Controller;


use App\Entity\Article;
use App\Entity\User;
use App\AppBundle\Form\AddArticleForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("/article/add", name="addArticle")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function add(Request $request){


        $error=null;
        $article = new Article();
        $form = $this->createForm(AddArticleForm::class,$article);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try{

                $article->setPublicationDate(new \DateTime('now'));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute('index');
            }
            catch (\Exception $exception){
                $error=$exception->getMessage();
                return $this->render(
                    'article/add_article_form.html.twig',
                    array('form'=>$form->createView(),
                        'error'=>$error)
                );
            }

        }

        return $this->render(
            'article/add_article_form.html.twig',
            array('form'=>$form->createView(),
                 'error'=>$error)
        );
    }

    /**
     * @Route("/article/delete/{id}", name="deleteArticle")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function delete($id){

        $entityManager = $this->getDoctrine()->getEntityManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('index');


    }
}