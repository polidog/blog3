<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2015/12/01
 * Time: 10:12
 */

namespace AppBundle\Controller\Blog;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller\Blog
 *
 * @Route("/blog")
 */
class DefaultController extends Controller
{
    /**
     * @Route()
     * @Method("GET")
     * @Template(":blog/default:index.html.twig")
     */
    public function indexAction()
    {
        return [
            'posts' => $this->getDoctrine()->getRepository('AppBundle:Post')->findAll()
        ];
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     * @Template(":blog/default:show.html.twig",vars={"post"})
     */
    public function showAction(Post $post)
    {
    }

    /**
     * @Route("/{id}/delete", requirements={"id":"\d+"})
     * @Method("GET")
     * @param Post $post
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('app_blog_default_index');
    }

    /**
     * @Route("/new")
     * @Method("GET")
     * @Template(":blog/default:new.html.twig")
     *
     * @return array
     */
    public function newAction()
    {
        $form = $this->postForm();
        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new")
     * @Method("POST")
     * @Template(":blog/default:new.html.twig")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $form = $this->postForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $post = $form->getData();
            $post->setCreatedAt(new \DateTime());
            $post->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('app_blog_default_index');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function postForm()
    {
        return $this->createFormBuilder(new Post())
            ->add('title')
            ->add('body')
            ->getForm();
    }
}