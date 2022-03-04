<?php

namespace App\Entity;

use App\Repository\StatueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatueRepository::class)
 */
class Statue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="Statue")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity=Lignefacture::class, mappedBy="Statue")
     */
    private $lignefactures;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="statue")
     */
    private $factures;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="statue")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="statue")
     */
    private $ligneCommandes;

    /**
     * @ORM\OneToMany(targetEntity=Devis::class, mappedBy="statue")
     */
    private $devis;

    /**
     * @ORM\OneToMany(targetEntity=Lignedevis::class, mappedBy="statue")
     */
    private $lignedevis;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->lignefactures = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->ligneCommandes = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->lignedevis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function __toString(){
        return $this->nom;
    }



    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    /**
     * @return Collection|Lignefacture[]
     */
    public function getLignefactures(): Collection
    {
        return $this->lignefactures;
    }

    public function addLignefacture(Lignefacture $lignefacture): self
    {
        if (!$this->lignefactures->contains($lignefacture)) {
            $this->lignefactures[] = $lignefacture;
            $lignefacture->setStatue($this);
        }

        return $this;
    }

    public function removeLignefacture(Lignefacture $lignefacture): self
    {
        if ($this->lignefactures->removeElement($lignefacture)) {
            // set the owning side to null (unless already changed)
            if ($lignefacture->getStatue() === $this) {
                $lignefacture->setStatue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Facture[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setStatue($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getStatue() === $this) {
                $facture->setStatue(null);
            }
        }

        return $this;
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
            // set the owning side to null (unless already changed)
            if ($commande->getStatue() === $this) {
            }
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
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {

        }

        return $this;
    }

    /**
     * @return Collection|Devis[]
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis[] = $devi;
            $devi->setStatue($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getStatue() === $this) {
                $devi->setStatue(null);
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
            $lignedevi->setStatue($this);
        }

        return $this;
    }

    public function removeLignedevi(Lignedevis $lignedevi): self
    {
        if ($this->lignedevis->removeElement($lignedevi)) {
            // set the owning side to null (unless already changed)
            if ($lignedevi->getStatue() === $this) {
                $lignedevi->setStatue(null);
            }
        }

        return $this;
    }
}
