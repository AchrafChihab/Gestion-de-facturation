<?php

namespace App\Entity;

use App\Repository\LignedevisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LignedevisRepository::class)
 */
class Lignedevis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Statue::class, inversedBy="lignedevis")
     */
    private $statue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $qte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="lignedevis")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity=Devis::class, inversedBy="lignedevis")
     */
    private $devis;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQte(): ?string
    {
        return $this->qte;
    }

    public function setQte(string $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        return $this;
    }
}
