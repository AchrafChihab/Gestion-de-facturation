<?php

namespace App\Entity;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FactureRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)

 */
class Facture
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
     * @ORM\ManyToOne(targetEntity=Statue::class, inversedBy="factures")
     */
    private $statue;

    /**
     * @ORM\OneToMany(targetEntity=Lignefacture::class, mappedBy="facture", cascade={"persist", "remove"})
     */
    private $lignesfacture;

    /**
     * @ORM\ManyToOne(targetEntity=Clients::class, inversedBy="factures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;


    public function __construct()
    {
        $this->lignesfacture = new ArrayCollection();
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
     * @return Collection|Lignefacture[]
     */
    public function getLignesfacture(): Collection
    {
        return $this->lignesfacture;
    }

    public function addLignesfacture(Lignefacture $lignesfacture): self
    {
        if (!$this->lignesfacture->contains($lignesfacture)) {
            $this->lignesfacture[] = $lignesfacture;
            $lignesfacture->setFacture($this);
        }
        return $this;
    }

    public function removeTag(Lignefacture $lignesfacture): void
    {
        $this->tags->removeElement($lignesfacture);
    }

    public function removeLignesfacture(Lignefacture $lignesfacture): self
    {
        if ($this->lignesfacture->removeElement($lignesfacture)) {
            // set the owning side to null (unless already changed)
            if ($lignesfacture->getFacture() === $this) {
                $lignesfacture->setFacture(null);
            }
        }
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
}
