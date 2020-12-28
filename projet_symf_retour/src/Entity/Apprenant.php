<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "get_un_app"={
 *               "method"="GET",
 *                   "path"="apprenants",
 *                   "normalization_context"={"groups"={"get_un_app:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "addApprenant"={
 *              "route_name"="addApprenant",
 *              "method"="POST",
 *              "deserialize"=false
 *           }
 *     },
 *        itemOperations={
 *
 *           "get_deux_app"={
 *               "method"="GET",
 *                   "path"="/apprenants/{id}",
 *                   "normalization_context"={"groups"={"get_deux_app:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *
 *
 *}
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants", cascade={"persist"})
     */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
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
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }
}
