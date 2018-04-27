<?php

namespace App\Controller;

    use App\Entity\Article;
      use Symfony\Component\HttpFoundation\Request;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

  

class ArticleController extends Controller{

        /**
         * @Route("/", name="homepage")
         * @Method({"GET"})
         */
        public function index(){

          
           $articles =$this->getDoctrine()->getRepository(Article::class)->findAll();

            return $this->render("articles/index.html.twig",array('article'=>$articles));
        }
        
        /**
        * @Route("/template/{id}")
        *@Method({"GET"})
        */
        public function template($id){
            $isElementArticle = new Article();
            
            $entityManager=$this->getDoctrine();
            $isElementArticle =$entityManager->getRepository(Article::class)->find($id);
            return $this->render("articles/header.html.twig",array('ele'=>$isElementArticle));
        }

        /**
         *@Route("/create")
         * Method({"GET","POST"})
         */


         public function create(Request $request){
            
            $isArticle = new Article();

            $form=$this->createFormBuilder($isArticle)
            ->add('nom',TextType::class,array('attr'=>array('class'=>'form-control'),'required'=>'true'))
            ->add('prenom',TextType::class,array('attr'=>array('class'=>'form-control'),'required'=>'true'))
            ->add('comment',TextareaType::class,array('attr'=>array('class'=>'form-control message'),'required'=>'true'))
            ->add('img',ChoiceType::class,array( 'attr'=>array('class'=>'custom-select'),'choices'=>array('Photo 1'=>'https://picsum.photos/290/290/?random','Photo 2'=>'https://picsum.photos/g/290/290','Photo 3'=>'https://picsum.photos/290/290/?gravity=east','Photo 4'=>'https://picsum.photos/290/290/?gravity=west',"Photo 5"=>'https://picsum.photos/290/290/?gravity=north',"Photo 6"=>"https://picsum.photos/290/290/?gravity=south",'Photo 7'=>"https://picsum.photos/g/290/290?random",'Photo 8'=>'https://picsum.photos/g/290/290?gravity=west')))
            ->add('save',SubmitType::class,array('attr'=>array('class'=>'btn btn-dark send'),'label'=>'Create'))
            ->getForm();

                $form->handleRequest($request);
                if($form->isSubmitted()&& $form->isValid()){
                    $isArticle=$form->getData();
                    $entityManager =$this->getDoctrine()->getManager();
                    $entityManager->persist($isArticle);
                    $entityManager->flush();
                    return $this->redirectToRoute("homepage");
                }

            return $this->render("articles/create.html.twig",array("form"=>$form->createView()));
         }
        

         /**
          *@Route("/update/{id}", name="update")
          *@Method({"GET","POST"})
          */
    
          /*ELEMENT: UPDATE */
          public function update(Request $request,$id){
           
            $elementArticle = new Article();
            $entityManager=$this->getDoctrine();
            $elementArticle =$entityManager->getRepository(Article::class)->find($id);
            

            $form=$this->createFormBuilder($elementArticle)
            ->add('nom',TextType::class,array('attr'=>array('class'=>'form-control'),'required'=>'true'))
            ->add('prenom',TextType::class,array('attr'=>array('class'=>'form-control'),'required'=>'true'))
            ->add('comment',TextareaType::class,array('attr'=>array('class'=>'form-control message'),'required'=>'true'))
            ->add('img',TextType::class,array( 'attr'=>array('class'=>'form-control'),'required'=>'true'))
            ->add('save',SubmitType::class,array('attr'=>array('class'=>'btn btn-dark send'),'label'=>'Update'))
            ->getForm();

                $form->handleRequest($request);
                if($form->isSubmitted()&& $form->isValid()){
                  
                    $entityManager =$this->getDoctrine()->getManager();
                    $entityManager->flush();
                    return $this->redirectToRoute("homepage");
                }

           

            return $this->render("articles/update.html.twig",array("form"=>$form->createView()));

          }


/*ELEMENT: DELETE */
          /**
           * @Route("/delete/{id}")
           * @Method({"GET","POST"})
           */
          public function delete($id){
            $entityManager=$this->getDoctrine()->getManager();
            $isDeleteArticle =$entityManager->getRepository(Article::class)->find($id);
            $entityManager->remove($isDeleteArticle);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');
          }
    }

