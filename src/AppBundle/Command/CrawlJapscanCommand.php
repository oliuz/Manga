<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use AppBundle\Entity\Manga;

class CrawlJapscanCommand extends ContainerAwareCommand {
  
  //private static $url = "http://www.japscan.com/mangas/";
  const url = "http://www.japscan.com/mangas/";
  const rss = "http://www.japscan.com/rss/";
  
  private static $all;
  private static $rss;
  private static $names;
  private static $cname;
  private static $em;
  private static $client;

  protected function configure() {
    $this
      ->setName('crawl:japscan')
      ->addOption('all', null, InputOption::VALUE_NONE, 'Récupére tous les mangas')
      ->addOption('name', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Mangas spécifiques à récupérer')
      ->addOption('rss', null, InputOption::VALUE_NONE, 'Récupére le flux rss et récupére les mangas')
      ->setDescription("Récupére la liste des mangas du site japscan.com en fonction de l'option choisie")
    ;
  }
  
  protected function initialize(InputInterface $input, OutputInterface $output) {
    self::$all = ($input->getOption('all')) ? 1 : 0;
    self::$rss = ($input->getOption('rss')) ? 1 : 0;
    self::$names = $input->getOption('name');
    self::$cname = (count(self::$names) >= 1) ? 1 : 0;    
    self::$em = $this->getContainer()->get('doctrine')->getManager();
    self::$client = new Client();
  }
  
  protected function interact(InputInterface $input, OutputInterface $output) {
    $options = self::$all + self::$rss + self::$cname;
    
    if($options == 0) {
      $output->writeln('<error>Vous devez choisir une option (all, rss, name)</error>');
      exit();
    }
    else if($options > 1) {
      $output->writeln('<error>Vous devez choisir une seule option</error>');
      exit();
    }
    
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    
    $output->write('<info>Début traitement</info> ');
    
    if(self::$all == 1) {
      $output->write('<comment>Mode all</comment>');
      $names = array();
    }
    else if(self::$cname == 1) {
      $output->write('<comment>Mode name</comment>');
      $names = self::$names;
    }
    else if(self::$rss == 1) {
      $output->write('<comment>Mode rss</comment>');
      $names = $this->getMangaNamesByRss();
    }
    else {
      throw new Exception("Aucun mode de défini");
    }
    $output->writeln('');
    
    $liste = self::$client->request('GET', self::url);
    
    /* @var $nodes Crawler[] */
    $nodes = $liste->filter('#liste_mangas > div.row')->each(function ($node) {
      return $node->children(); //Récupére les div.cell
    });
    
    /* @var $node Crawler */
    foreach ($nodes as $node) {
      $name = $node->getNode(0)->nodeValue;
      
      if(count($names) == 0 || in_array($name, $names)) {              
        $output->writeln('  ' . $name);

        $manga = self::$em->getRepository("AppBundle:Manga")->findOneByName($name);
        if($manga === null) {
          $manga = new Manga();
          $manga->setName($name);
          self::$em->persist($manga);
        }
        $manga->setGenre($node->getNode(1)->nodeValue);
        $manga->setStatus($node->getNode(2)->nodeValue);

        $link = $liste->selectLink($manga->getName())->link();
        $crawler = self::$client->click($link);

        //Rajouter un test pour éviter que ça plante !
        if($crawler->filter("#synopsis")->count() > 0) {
          $manga->setSynopsis($crawler->filter("#synopsis")->text());
        }
        $manga->setAuthor($crawler->filter("div.row")->children()->getNode(0)->nodeValue);
        
        $child1 = $crawler->filter("div.row")->children()->getNode(1)->nodeValue;
        $child2 = $crawler->filter("div.row")->children()->getNode(2)->nodeValue;        
        
        if(intval($child1) > 1900) {
          $manga->setYearStart(intval($child1));
          $manga->setOtherName(null);
        }
        else {
          $manga->setOtherName($child1);
          if(intval($child2) > 1900) {
            $manga->setYearStart(intval($child2));
          }
        }
        
        $manga->addUrl($crawler->getBaseHref());
        self::$em->flush();
      }
    }
    
    $output->writeln('<info>Fin traitement</info>');
  }
  
  private function getMangaNamesByRss() {
    $names = [];
    $liste = self::$client->request('GET', self::rss);
    
    $nodes = $liste->filter('item')->each(function ($node) {
      return $node->children()->getNode(1)->nodeValue;
    });
    
    foreach ($nodes as $node) {
      $crawler = self::$client->request('GET', $node);
      $name = $crawler->filter('#breadcrumb > div:nth-child(3) > a > span')->text();
      if(!in_array($name, $names)) {
        array_push($names, $name);
      }
    }
    
    return $names;
  }
}
