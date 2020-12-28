<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getPrRfApFr:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"getPrRfApFr:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"getPrRfApFr:read"})
     */
    private $datedebut;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"getPrRfApFr:read"})
     */
    private $datefin;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promos")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos")
     * @Groups({"getPrRfApFr:read"})
     */
    private $referentiels;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?\DateTimeInterface
    {
        return $this->libelle;
    }

    public function setLibelle(\DateTimeInterface $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromos($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromos() === $this) {
                $groupe->setPromos(null);
            }
        }

        return $this;
    }

    public function getReferentiels(): ?Referentiel
    {
        return $this->referentiels;
    }

    public function setReferentiels(?Referentiel $referentiels): self
    {
        $this->referentiels = $referentiels;

        return $this;
    }
}
