<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "getPrRfApFr"={
 *                   "method"="GET",
 *                   "path"="admin/groupes",
 *                   "normalization_context"={"groups"={"getPrRfApFr:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *      "getAllReferentielGrpC"={
 *                   "method"="GET",
 *                   "path"="admin/referentiels/grpecompetences",
 *                   "normalization_context"={"groups"={"getallrefgrpc:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "postrefcomp"={
 *               "method"="POST",
 *                   "path"="admin/referentiels",
 *                   
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 *        itemOperations={
 *           "getByIdRefCompe"={
 *               "method"="GET",
 *                   "path"="admin/referentiels/{id}",
 *                   "normalization_context"={"groups"={"getByIdRefCompe:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "getByIdRefCompeGrpc"={
 *               "method"="GET",
 *                   "path"="admin/referentiels/{id_f}/grpecompetences/{id}",
 *                   "normalization_context"={"groups"={"getByIdRefCompeGrpc:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "putajoutsupref"={
 *               "method"="PUT",
 *                   "path"="admin/referentiels/{id}",
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "denormalization_context"={"groups"={"putajoutsupref:write"}},
 *                   "security_message"="Vous n'avez pas access à cette Ressource"  
 *          }
 *     }
 *      )
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getPrRfApFr:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getPrRfApFr:read"})
     */
    private $nomgroupe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getPrRfApFr:read"})
     */
    private $typegroupe;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     * @Groups({"getPrRfApFr:read"})
     * ApiSubresource
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     */
    private $formateurs;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomgroupe(): ?string
    {
        return $this->nomgroupe;
    }

    public function setNomgroupe(string $nomgroupe): self
    {
        $this->nomgroupe = $nomgroupe;

        return $this;
    }

    public function getTypegroupe(): ?string
    {
        return $this->typegroupe;
    }

    public function setTypegroupe(string $typegroupe): self
    {
        $this->typegroupe = $typegroupe;

        return $this;
    }

    public function getPromos(): ?Promo
    {
        return $this->promos;
    }

    public function setPromos(?Promo $promos): self
    {
        $this->promos = $promos;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

        return $this;
    }
}
