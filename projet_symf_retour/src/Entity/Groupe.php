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
 *          "getApp"={
 *                   "method"="GET",
 *                   "path"="admin/groupes/apprenants",
 *                   "normalization_context"={"groups"={"getApp:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "postAppForm"={
 *               "method"="POST",
 *                   "path"="admin/groupes",
 *                   "denormalization_context" = {"groups"={"postAppForm:write"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 *        itemOperations={
 *           "getByIdApp"={
 *               "method"="GET",
 *                   "path"="admin/groupes/{id}",
 *                   "normalization_context"={"groups"={"getByIdApp:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "putApp"={
 *               "method"="PUT",
 *                   "path"="admin/groupes/{id}",
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "denormalization_context" = {"groups"={"putApp:write"}},
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "delAppGrp"={
 *               "method"="DELETE",
 *                   "path"="admin/groupes/{ida}/apprenants/{id}",
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "denormalization_context" = {"groups"={"delAppGrp:write"}},
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     }
 *      )
 * 
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getPrRfApFr:read","getApp:read","postAppForm:write","getByIdApp:read","putApp:write","delAppGrp:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getPrRfApFr:read","getApp:read","postAppForm:write","getByIdApp:read","putApp:write","delAppGrp:write"})
     */
    private $nomgroupe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getPrRfApFr:read","getApp:read","postAppForm:write","getByIdApp:read","putApp:write","delAppGrp:write"})
     */
    private $typegroupe;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     * @Groups({"getPrRfApFr:read"})
     * ApiSubresource
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes", cascade={"persist"})
     * @Groups({"getPrRfApFr:read","getApp:read","postAppForm:write","getByIdApp:read","putApp:write","delAppGrp:write"})
     * @ApiSubresource
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes", cascade={"persist"})
     * @Groups({"getPrRfApFr:read","postAppForm:write"})
     * @ApiSubresource
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
