<?php

namespace Adidas\Bundle\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AdidasPostBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function postsAction()
    {
        $postRand = array();
        $branding = $this->get('site_context')->getCurrentBranding();
        $em = $this->getDoctrine()->getManager();
        $postTwt = $this->get('adidas.repository.post')                
            ->getPostByClub('twitter',5);
        $nbTwt = count($postTwt);
        $postsInst = $this->get('adidas.repository.post')                
            ->getPostByClub('instagram',10-$nbTwt);        
        return $this->render('AdidasPostBundle:Post:posts.html.twig', array('instag' => $postsInst, 'twitter' => $postTwt));
    }
    
  
}
