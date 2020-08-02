<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
use App\Services\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


//[dot] in post name is just for separation
/**
 * @Route("/post", name="post.")
 */

class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
        //we want to display all the posts that we have
        //A repository is a way to manipulate and access your entity. If you want to access all the posts, you will not
        //select all the posts within the Entity folder and giving them path name for each.
        // You just go to the PostRepository.php and ask all the values that it has stored.
        //for that we need to do Dependency Injection. See the parameters added above in the function

        $posts = $postRepository ->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]); //now we throw the posts into our view, into the twig file
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function create(Request $request, FileUploader $fileUploader){

        //create a new post with title
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        //process forms
        $form->handleRequest($request);


        if($form->isSubmitted()){
            //entity manager (what connects and talks to our database)
            $em = $this->getDoctrine()->getManager();
            $file = dump($request->files->get('post')['attachment']);
            if($file){

                $filename = $fileUploader->uploadFile($file);


                //now we set the filename to our database
                $post->setImage($filename);
                $em->persist($post);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('post.index'));
        }


        //return a response
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //creating a function to show the post we are asking for

    /**
     * @Route("/show/{id}", name="show")
     * @param Post $post
     * @return Response
     */
    public function show(Post $post){
        //create the show view when you click on a post
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param Post $post
     */
    public function remove(Post $post){
        //we need the entity manager to be able to remove
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post was removed!'); //addflash is a function that takes advantage of a session and add a message to be displayed in that session


        //each action needs to return a response. Redirect in symfony is response
        return $this->redirect($this->generateUrl('post.index'));
    }

}
