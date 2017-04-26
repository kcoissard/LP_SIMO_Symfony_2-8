<?php

namespace sil14\VitrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 */
class Article
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $prix;

    /**
     * @var boolean
     */
    private $promotion;

    /**
     * @var integer
     */
    private $stock;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ligneCommande;

    /**
     * @var \sil14\VitrineBundle\Entity\Categorie
     */
    private $categorie;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ligneCommande = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     * @return Article
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set prix
     *
     * @param string $prix
     * @return Article
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set promotion
     *
     * @param boolean $promotion
     * @return Article
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return boolean 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     * @return Article
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Add ligneCommande
     *
     * @param \sil14\VitrineBundle\Entity\Article $ligneCommande
     * @return Article
     */
    public function addLigneCommande(\sil14\VitrineBundle\Entity\Article $ligneCommande)
    {
        $this->ligneCommande[] = $ligneCommande;

        return $this;
    }

    /**
     * Remove ligneCommande
     *
     * @param \sil14\VitrineBundle\Entity\Article $ligneCommande
     */
    public function removeLigneCommande(\sil14\VitrineBundle\Entity\Article $ligneCommande)
    {
        $this->ligneCommande->removeElement($ligneCommande);
    }

    /**
     * Get ligneCommande
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLigneCommande()
    {
        return $this->ligneCommande;
    }

    /**
     * Set categorie
     *
     * @param \sil14\VitrineBundle\Entity\Categorie $categorie
     * @return Article
     */
    public function setCategorie(\sil14\VitrineBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \sil14\VitrineBundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    
    public function findByCategorie($idCategorie){
        $qb = $this->createQueryBuilder('article');

        $qb
          ->where('article.categorie = :categorie')
          ->setParameter('categorie', $idCategorie)
        ;

        return $qb
          ->getQuery()
          ->getResult()
        ;
    }
}
