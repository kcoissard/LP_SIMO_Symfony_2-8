<?php

namespace sil14\VitrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneCommande
 */
class LigneCommande
{
    /**
     * @var string
     */
    private $prix;

    /**
     * @var \sil14\VitrineBundle\Entity\Article
     */
    private $article;
    
    /**
     * @var \sil14\VitrineBundle\Entity\Commande
     */
    private $commande;


    /**
     * Set prix
     *
     * @param string $prix
     * @return LigneCommande
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
     * Set article
     *
     * @param \sil14\VitrineBundle\Entity\Article $article
     * @return LigneCommande
     */
    public function setArticle(\sil14\VitrineBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \sil14\VitrineBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }
    
    
    /**
     * Set commande
     *
     * @param \sil14\VitrineBundle\Entity\Commande $commande
     * @return LigneCommande
     */
    public function setCommande(\sil14\VitrineBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \sil14\VitrineBundle\Entity\Commande 
     */
    public function getCommande()
    {
        return $this->commande;
    }

    
    
    
    /**
     * @var integer
     */
    private $id;


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
     * @var integer
     */
    private $quantite;


    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return LigneCommande
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
    
    
     /**
      * Trouver les ligneCommande d'une commande
      * @param type $idCommande
      * @return type
      */
     public function findByCommande($idCommande){
        $qb = $this->createQueryBuilder('ligneCommande');

        $qb
          ->where('ligneCommande.commande = :commande')
          ->setParameter('commande', $idCommande)
        ;

        return $qb
          ->getQuery()
          ->getResult()
        ;
    }
}
