<?php
// src/OC/ReaderBundle/DataFixtures/ORM/TokyoGhoulRe.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class Mangas extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
  private $container;

  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  public function load(ObjectManager $em) {
  
    /* @var $crawl AppBundle\Command\CrawlJapscanCommand */
    //$crawl = $this->container->get('app.command.crawl_japscan');
    
    $command = new \AppBundle\Command\CrawlJapscanCommand();
    $command->setContainer($this->container);
    $input = new ArrayInput(array('--all'=>true));
    $output = new ConsoleOutput();
    $resultCode = $command->run($input, $output);
    //print_r($resultCode);
    //print_r($output);
  }

  public function getOrder() {
    return 1; // the order in which fixtures will be loaded
  }
}