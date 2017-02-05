<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Manga
 *
 * @ORM\Table(name="manga")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MangaRepository")
 */
class Manga
{
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=255, unique=true)
   */
  private $name;
  
  /**
   * @Gedmo\Slug(fields={"name"})
   * @ORM\Column(name="slug", type="string", length=255, unique=true)
   */
  private $slug;
  
  /**
   * @var string
   *
   * @ORM\Column(name="other_name", type="string", length=255, nullable=true)
   */
  private $otherName;

  /**
   * @var string
   *
   * @ORM\Column(name="status", type="string", length=255, nullable=true)
   */
  private $status;

  /**
   * @var string
   *
   * @ORM\Column(name="genre", type="string", length=255, nullable=true)
   */
  private $genre;

  /**
   * @var string
   *
   * @ORM\Column(name="synopsis", type="text", nullable=true)
   */
  private $synopsis;

  /**
   * @var string
   *
   * @ORM\Column(name="author", type="string", length=255, nullable=true)
   */
  private $author;

  /**
   * @var int
   *
   * @ORM\Column(name="year_start", type="integer", nullable=true)
   */
  private $yearStart;

  /**
   * @var int
   *
   * @ORM\Column(name="year_end", type="integer", nullable=true)
   */
  private $yearEnd;

  /**
   * @var string
   *
   * @ORM\Column(name="url", type="array", nullable=true)
   */
  private $url;
  
  /**
   * @var string
   *
   * @ORM\Column(name="state", type="string", length=255, nullable=true)
   */
  private $state;
  
  /**
   * @var string
   *
   * @ORM\Column(name="opinion", type="text", nullable=true)
   */
  private $opinion;

  public function __construct() {
    $this->url = [];
  }
  
  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set name
   *
   * @param string $name
   *
   * @return Manga
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get name
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set otherName
   *
   * @param string $otherName
   *
   * @return Manga
   */
  public function setOtherName($otherName)
  {
    $this->otherName = $otherName;

    return $this;
  }

  /**
   * Get otherName
   *
   * @return string
   */
  public function getOtherName()
  {
    return $this->otherName;
  }

  /**
   * Set status
   *
   * @param string $status
   *
   * @return Manga
   */
  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }

  /**
   * Get status
   *
   * @return string
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * Set genre
   *
   * @param string $genre
   *
   * @return Manga
   */
  public function setGenre($genre)
  {
    $this->genre = $genre;

    return $this;
  }

  /**
   * Get genre
   *
   * @return string
   */
  public function getGenre()
  {
    return $this->genre;
  }

  /**
   * Set synopsis
   *
   * @param string $synopsis
   *
   * @return Manga
   */
  public function setSynopsis($synopsis)
  {
    $this->synopsis = $synopsis;

    return $this;
  }

  /**
   * Get synopsis
   *
   * @return string
   */
  public function getSynopsis()
  {
    return $this->synopsis;
  }

  /**
   * Set author
   *
   * @param string $author
   *
   * @return Manga
   */
  public function setAuthor($author)
  {
    $this->author = $author;

    return $this;
  }

  /**
   * Get author
   *
   * @return string
   */
  public function getAuthor()
  {
    return $this->author;
  }

  /**
   * Set yearStart
   *
   * @param integer $yearStart
   *
   * @return Manga
   */
  public function setYearStart($yearStart)
  {
   if($yearStart != 0) {
    $this->yearStart = $yearStart;
   }
   
   return $this;
  }

  /**
   * Get yearStart
   *
   * @return integer
   */
  public function getYearStart()
  {
    return $this->yearStart;
  }

  /**
   * Set yearEnd
   *
   * @param integer $yearEnd
   *
   * @return Manga
   */
  public function setYearEnd($yearEnd)
  {
    $this->yearEnd = $yearEnd;

    return $this;
  }

  /**
   * Get yearEnd
   *
   * @return integer
   */
  public function getYearEnd()
  {
    return $this->yearEnd;
  }

  /**
   * Set url
   *
   * @param array $url
   *
   * @return Manga
   */
  public function setUrl($url)
  {
    $this->url = $url;

    return $this;
  }

  /**
   * Get url
   *
   * @return array
   */
  public function getUrl()
  {
    return $this->url;
  }
  
  public function addUrl($url) {
    if(!in_array($url, $this->url)) {
      array_push($this->url, $url);
    }
    return $this;
  }
  
  public function removeUrl($url) {
    if(in_array($url, $this->url)) {
      unset($this->url[array_search($url, $this->url)]);
    }
    return $this;
  }

  /**
   * Set slug
   *
   * @param string $slug
   *
   * @return Manga
   */
  public function setSlug($slug)
  {
    $this->slug = $slug;

    return $this;
  }

  /**
   * Get slug
   *
   * @return string
   */
  public function getSlug()
  {
    return $this->slug;
  }

  /**
   * Set state
   *
   * @param string $state
   *
   * @return Manga
   */
  public function setState($state)
  {
    $this->state = $state;

    return $this;
  }

  /**
   * Get state
   *
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }

  /**
   * Set opinion
   *
   * @param string $opinion
   *
   * @return Manga
   */
  public function setOpinion($opinion)
  {
    $this->opinion = $opinion;

    return $this;
  }

  /**
   * Get opinion
   *
   * @return string
   */
  public function getOpinion()
  {
    return $this->opinion;
  }
}
