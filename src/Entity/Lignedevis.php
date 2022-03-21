<?php

namespace App\Entity;

use App\Outil\Outil;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LignedevisRepository;

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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datede;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datea;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateDe(): ?\DateTimeInterface
    {
        return $this->datede;
    }

    public function setDateDe(?\DateTimeInterface $datede): self
    {
        $this->datede = $datede;

        return $this;
    }

    public function getDateA(): ?\DateTimeInterface
    {
        return $this->datea;
    }

    public function setDateA(?\DateTimeInterface $datea): self
    {
        $this->datea = $datea;

        return $this;
    }

    public function getDateFormatDebut(){
        return Outil::getDateFormatingsansday($this->datede->format('Y-m-d')); 
    }
    public function getDateFormatFin(){
        return Outil::getDateFormatingsansday($this->datea->format('Y-m-d'));       
    }
}
