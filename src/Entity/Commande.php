<?php

namespace App\Entity;

use App\Entity\Statue;
use App\Entity\Clients;
use App\Entity\Facture;
use App\Entity\Lignefacture;
use App\Entity\LigneCommande;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Clients::class, inversedBy="commandes")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Statue::class, inversedBy="commandes")
     */
    private $statue; 
    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="commande",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $lignecommandes;

    public function __construct()
    {  
        $this->lignecommandes = new ArrayCollection();
    }

    public function __toString(){
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getStatue(): ?Statue
    {
        return $this->statue;
    }
    public function setStatue(?Statue $statue): self
    {
        $this->statue = $statue;

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLignecommandes(): Collection
    {
        return $this->lignecommandes;
    }

    public function addLignecommande(LigneCommande $lignecommande): self
    {
        if (!$this->lignecommandes->contains($lignecommande)) {
            $this->lignecommandes[] = $lignecommande;
            $lignecommande->setCommande($this);
        }

        return $this;
    }

    public function removeLignecommande(LigneCommande $lignecommande): self
    {
        if ($this->lignecommandes->removeElement($lignecommande)) {
            // set the owning side to null (unless already changed)
            if ($lignecommande->getCommande() === $this) {
                $lignecommande->setCommande(null);
            }
        }

        return $this;
    } 

    public function convertfacture(){
        $facture = new Facture();

        $facture->setNom($this->getNom()); 
        $facture->setClient($this->getClient()); 
        $facture->setStatue($this->getStatue()); 
        foreach($this->getLignecommandes() as $ligne ){
            $newligne = new Lignefacture(); 
            $newligne->setPrix($ligne->getPrix()); 
            $newligne->setQte($ligne->getQte()); 
            $newligne->setService($ligne->getService()); 
            $newligne->setFacture($facture);
            $facture->addLignesfacture($newligne);
        }
        return $facture;
        
    }
 
 
}
