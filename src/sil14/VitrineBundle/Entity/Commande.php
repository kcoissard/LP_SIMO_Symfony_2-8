<?php

namespace sil14\VitrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 */
class Commande
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $valide;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ligneCommandes;

    /**
     * @var \sil14\VitrineBundle\Entity\Client
     */
    private $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ligneCommandes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set valide
     *
     * @param \boolean $valide
     * @return Commande
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return \boolean 
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Add ligneCommandes
     *
     * @param \sil14\VitrineBundle\Entity\LigneCommande $ligneCommandes
     * @return Commande
     */
    public function addLigneCommande(\sil14\VitrineBundle\Entity\LigneCommande $ligneCommandes)
    {
        $this->ligneCommandes[] = $ligneCommandes;

        return $this;
    }

    /**
     * Remove ligneCommandes
     *
     * @param \sil14\VitrineBundle\Entity\LigneCommande $ligneCommandes
     */
    public function removeLigneCommande(\sil14\VitrineBundle\Entity\LigneCommande $ligneCommandes)
    {
        $this->ligneCommandes->removeElement($ligneCommandes);
    }

    /**
     * Get ligneCommandes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLigneCommandes()
    {
        return $this->ligneCommandes;
    }

    /**
     * Set client
     *
     * @param \sil14\VitrineBundle\Entity\Client $client
     * @return Commande
     */
    public function setClient(\sil14\VitrineBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \sil14\VitrineBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }
}
