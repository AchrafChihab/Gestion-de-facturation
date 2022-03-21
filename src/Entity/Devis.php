<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Outil\Outil;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Repository\DevisRepository;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo; 


/**
 * @ORM\Entity(repositoryClass=DevisRepository::class)
 */
class Devis
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
     * @ORM\ManyToOne(targetEntity=Statue::class, inversedBy="devis")
     */
    private $statue;

    /**
     * @ORM\ManyToOne(targetEntity=Clients::class, inversedBy="devis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=Lignedevis::class, mappedBy="devis", cascade={"persist", "remove"})
     */
    private $lignedevis;

    /**
     * @var DateTime $created
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    public function __construct()
    {
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

    public function getStatue(): ?Statue
    {
        return $this->statue;
    }

    public function setStatue(?Statue $statue): self
    {
        $this->statue = $statue;

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
            $lignedevi->setDevis($this);
        }

        return $this;
    }

    public function removeLignedevi(Lignedevis $lignedevi): self
    {
        if ($this->lignedevis->removeElement($lignedevi)) {
            // set the owning side to null (unless already changed)
            if ($lignedevi->getDevis() === $this) {
                $lignedevi->setDevis(null);
            }
        }

        return $this;
    }

    public function convertcommande(){
        $commande = new Commande();

        $commande->setNom($this->getNom()); 
        $commande->setClient($this->getClient()); 
        $commande->setStatue($this->getStatue()); 
        foreach($this->getLignedevis() as $ligne ){

            $newligne = new LigneCommande(); 
            $newligne->setPrix($ligne->getPrix()); 
            $newligne->setQte($ligne->getQte()); 
            $newligne->setService($ligne->getService()); 
            $newligne->setCommande($commande);
            $commande->addLigneCommande($newligne);

        }
        return $commande;
        
    }
    public function getCreatedAt() :?DateTime
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt() :?DateTime
    {
        return $this->updatedAt;
    }
    
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDateFormat(){
        return Outil::getDateFormatingsansday($this->createdAt->format('Y-m-d'));        
        return Outil::getDateFormatingsansday($this->updatedAt->format('Y-m-d'));
    }




    
    public function depluc(){ 
        $devi = new Devis(); 
        $devi->setClient($this->getClient()); 
        $devi->setStatue($this->getStatue());   
        foreach($this->getLignedevis() as $ligne ){ 
            $newligne = new Lignedevis(); 
            $newligne->setPrix($ligne->getPrix()); 
            $newligne->setQte($ligne->getQte()); 
            $newligne->setService($ligne->getService()); 
            $newligne->setDevis($devi);
            $devi->addLignedevi($newligne); 
        }
        return $devi; 
    }


}
