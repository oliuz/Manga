<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Manga;
use AppBundle\Form\MangaType;
use AppBundle\Form\SearchType;
/* Rajouter une étoile ici pour utiliser le préfix
* @Route("blog")
*/
class AppController extends Controller {
  /**
   * @Route("/", name="home")
   * @Method({"GET","POST"})
   */
  public function indexAction(Request $request) {
    $session = $request->getSession();
    $em = $this->getDoctrine()->getManager();
    /* @var $rep \AppBundle\Repository\MangaRepository */
    $rep = $em->getRepository("AppBundle:Manga");
    
    $manga = new Manga();
    $this->getSession($request, $manga);
    $form = $this->get('form.factory')->create(SearchType::class, $manga, array(
      'entity_manager' => $this->get('doctrine.orm.entity_manager')
    ));
    
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      if ($form->get('reset')->isClicked()) {
        $this->removeSession($request);
      }
      else {
        $this->setSession($request, $manga);
      }
      return $this->redirectToRoute('home');
    }
    
    /* @var $mangas[] \AppBundle\Entity\Manga */
    $mangas = $rep->getAll($session->get('name'), $session->get('status'), $session->get('genre'), $session->get('author'), $session->get('synopsis'), $session->get('url'), $session->get('state'), $session->get('opinion'));
    
    return $this->render('AppBundle:App:index.html.twig', array(
      'mangas' => $mangas,
      'form' => $form->createView()
    ));
  }
  
  /**
   * @Route("/test", name="test")
   * @Method({"GET"})
   */
  public function testAction(Request $request) {    
    /*$client = new \Goutte\Client();
    $liste = $client->request('GET', "http://www.japscan.com/rss/");
    
    $nodes = $liste->filter('item')->each(function ($node) {
      return $node->children()->getNode(1)->nodeValue;
    });
    
    $crawler = $client->request('GET', $nodes[0]);*/
    
    return $this->render('AppBundle:App:debug.html.twig', array(
      'var' => $request->getSession()->get('foo')
    ));
  }
  
  /**
   * @Route("/{slug}", name="view")
   * @Method({"GET","POST"})
   */
  public function viewAction(Request $request, Manga $manga) {
    $em = $this->getDoctrine()->getManager();
    $form = $this->get('form.factory')->create(MangaType::class, $manga);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->flush();
      return $this->redirectToRoute('home');
    }    
    return $this->render('AppBundle:App:view.html.twig', array(
      'form' => $form->createView()
    ));
  }
  
  private function getSession(Request $request, Manga $manga) {
    $session = $request->getSession();    
    $manga->setName($session->get('name'));
    $manga->setStatus($session->get('status'));
    $manga->setGenre($session->get('genre'));
    $manga->setAuthor($session->get('author'));
    $manga->setSynopsis($session->get('synopsis'));
    $manga->setUrl($session->get('url'));
    $manga->setState($session->get('state'));
    $manga->setOpinion($session->get('opinion'));
  }
  
  private function setSession(Request $request, Manga $manga) {
    $session = $request->getSession();    
    $session->set('name', $manga->getName());
    $session->set('status', $manga->getStatus());
    $session->set('genre', $manga->getGenre());
    $session->set('author', $manga->getAuthor());
    $session->set('synopsis', $manga->getSynopsis());
    $session->set('url', $manga->getUrl());
    $session->set('state', $manga->getState());
    $session->set('opinion', $manga->getOpinion());
  }
  private function removeSession(Request $request) {
    $session = $request->getSession();
    $session->remove('name');
    $session->remove('status');
    $session->remove('genre');
    $session->remove('author');
    $session->remove('synopsis');
    $session->remove('url');
    $session->remove('state');
    $session->remove('opinion');
  }
  
}
