<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * "denormalizationContext"={"groups"={"postrefcomp:write"}},
 * @ApiResource(
 *      collectionOperations={
 *          "getAllReferentiel"={
 *                   "method"="GET",
 *                   "path"="admin/referentiels",
 *                   "normalization_context"={"groups"={"getallCompetenceref:read"}},
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
 *  )
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 */
class Referentiel
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
     * @Groups({"getallCompetenceref:read","getallrefgrpc:read","postrefcomp:write","getByIdRefCompe:read","getByIdRefCompeGrpc:read","putajoutsupref:write","getPrRfApFr:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels",cascade={"persist"})
     * @Groups({"getallCompetenceref:read","getallrefgrpc:read","postrefcomp:write","getByIdRefCompe:read","getByIdRefCompeGrpc:read","putajoutsupref:write","getPrRfApFr:read"})
     * @ApiSubresource
     */
    private $groupecompetences;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiels")
     */
    private $promos;

    public function __construct()
    {
        $this->groupecompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupecompetences(): Collection
    {
        return $this->groupecompetences;
    }

    public function addGroupecompetence(GroupeCompetence $groupecompetence): self
    {
        if (!$this->groupecompetences->contains($groupecompetence)) {
            $this->groupecompetences[] = $groupecompetence;
        }

        return $this;
    }

    public function removeGroupecompetence(GroupeCompetence $groupecompetence): self
    {
        $this->groupecompetences->removeElement($groupecompetence);

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiels($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiels() === $this) {
                $promo->setReferentiels(null);
            }
        }

        return $this;
    }
}
