<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "getAllTag"={
 *              "method"="GET",
 *                  "path"="admin/tags",
 *                  "normalization_context"={"groups"={"getAllGrpTag:read"}},
 *                  "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "postGrTag"={
 *               "method"="POST",
 *                   "path"="admin/tags",
 *                   
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 *        itemOperations={
 *           "getByIdTag"={
 *               "method"="GET",
 *                   "path"="admin/grptags/{id}",
 *                   "normalization_context"={"groups"={"getByIdTag:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "putgrptag"={
 *               "method"="PUT",
 *                   "path"="admin/tags/{id}",
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "denormalization_context"={"groups"={"putgrptag:write"}},
 *                   "security_message"="Vous n'avez pas access à cette Ressource"  
 *          }
 *     }
 *  )
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * "denormalizationContext"={"groups"={"postGrTag:write"}},
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getAllTag:read","postTag:write","getByIdTag:read","getByIdTagGrpTag:read","putajoutsuptag:write","getAllGrpTag:read","postGrTag:write","putgrptag:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getAllTag:read","postTag:write","getByIdTag:read","getByIdTagGrpTag:read","putajoutsuptag:write","getAllGrpTag:read","postGrTag:write","putgrptag:write"})
     */
    private $nomTag;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tags",cascade={"persist"})
     * @Groups({"getAllGrpTag:read","postGrTag:write"})
     * @ApiSubresource
     */
    private $groupeTags;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTag(): ?string
    {
        return $this->nomTag;
    }

    public function setNomTag(string $nomTag): self
    {
        $this->nomTag = $nomTag;

        return $this;
    }

    /**
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
        }

        return $this;
    }
}
