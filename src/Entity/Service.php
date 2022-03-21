<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
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
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="service")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="service")
     */
    private $ligneCommandes;

    /**
     * @ORM\OneToMany(targetEntity=Lignedevis::class, mappedBy="service")
     */
    private $lignedevis;

    /**
     * @ORM\Column(type="boolean")
     */
    private $type;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->ligneCommandes = new ArrayCollection();
        $this->lignedevis = new ArrayCollection();
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

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            
        }

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes[] = $ligneCommande;
            $ligneCommande->setService($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getService() === $this) {
                $ligneCommande->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Lignedevis[]
     */
    public function getLignedevis(): Collection
    {
        return $this->lignedevis;
    }

    public function addLignedevi(Lignedevis $lignedevi): self
    {
        if (!$this->lignedevis->contains($lignedevi)) {
            $this->lignedevis[] = $lignedevi;
            $lignedevi->setService($this);
        }

        return $this;
    }

    public function removeLignedevi(Lignedevis $lignedevi): self
    {
        if ($this->lignedevis->removeElement($lignedevi)) {
            // set the owning side to null (unless already changed)
            if ($lignedevi->getService() === $this) {
                $lignedevi->setService(null);
            }
        }

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }
}
