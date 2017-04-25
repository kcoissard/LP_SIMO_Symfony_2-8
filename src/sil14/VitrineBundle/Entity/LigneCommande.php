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
     * @var integer
     */
    private $quantité;

    /**
     * @var \sil14\VitrineBundle\Entity\Categorie
     */
    private $categorie;


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
     * Set quantité
     *
     * @param integer $quantité
     * @return LigneCommande
     */
    public function setQuantité($quantité)
    {
        $this->quantité = $quantité;

        return $this;
    }

    /**
     * Get quantité
     *
     * @return integer 
     */
    public function getQuantité()
    {
        return $this->quantité;
    }

    /**
     * Set categorie
     *
     * @param \sil14\VitrineBundle\Entity\Categorie $categorie
     * @return LigneCommande
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
}
