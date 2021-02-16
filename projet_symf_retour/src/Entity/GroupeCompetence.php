<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "getAllGroupeCompetence"={
 *               "method"="GET",
 *                   "path"="admin/grpecompetences",
 *                   "normalization_context"={"groups"={"grpcomp:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *
*          },
 *          "get_un_un"={
 *               "method"="GET",
 *                   "path"="admin/grpecompetences/competences",
 *                   "normalization_context"={"groups"={"get_un_un_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "post_un"={
 *               "method"="POST",
 *                   "path"="admin/grpecompetences",
 *                   "normalization_context"={"groups"={"post_un_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 *        itemOperations={
 *           "getByIdGroupeCompetence"={
 *               "method"="GET",
 *                   "path"="admin/grpecompetences/{id}",
 *                   "normalization_context"={"groups"={"get_deux_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "get_deux_deux"={
 *               "method"="GET",
 *                   "path"="admin/grpecompetences/{id}/competences",
 *                   "normalization_context"={"groups"={"get_deux_deux_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "putGroupeCompetence"={
 *               "method"="PUT",
 *                   "path"="admin/grpecompetences/{id}",
 *                   "normalization_context"={"groups"={"put_un_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *
 *
 *}
 * ))
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"postcompetence:write","putajoutsupref:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_un_ad:read","grpcomp:read","get_deux_deux_ad:read","putCompetence:read","putCompetence:write","postcompetence:write","getallrefgrpc:read","postrefcomp:write","getByIdRefCompe:read","getByIdRefCompeGrpc:read","putajoutsupref:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_un_ad:read","get_deux_ad:read","get_deux_deux_ad:read","putCompetence:read","putCompetence:write","postcompetence:write","getallrefgrpc:read","postrefcomp:write","getByIdRefCompe:read","getByIdRefCompeGrpc:read","putajoutsupref:write"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences",cascade={"persist"})
     * @Groups({"get_un_ad:read","get_deux_ad:read","get_deux_deux_ad:read","postcompetence:write","getallrefgrpc:read","getByIdRefCompe:read","getByIdRefCompeGrpc:read"})
     */
    private $competence;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupecompetences",cascade={"persist"})
     */
    private $referentiels;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competence->removeElement($competence);

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGroupecompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGroupecompetence($this);
        }

        return $this;
    }
}
