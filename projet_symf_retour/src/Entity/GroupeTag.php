<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "getAllTag"={
 *              "method"="GET",
 *                  "path"="admin/grptags",
 *                  "normalization_context"={"groups"={"getAllTag:read"}},
 *                  "security"="is_granted('ROLE_ADMIN')",
 *                  "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "postTag"={
 *               "method"="POST",
 *                   "path"="admin/grptags",
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
 *          "getByIdTagGrpTag"={
 *               "method"="GET",
 *                   "path"="/admin/grptags/{id}/tags",
 *                   "normalization_context"={"groups"={"getByIdTagGrpTag:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource",
 *
 *          },
 *          "putajoutsuptag"={
 *               "method"="PUT",
 *                   "path"="admin/grptags/{id}",
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "denormalization_context"={"groups"={"putajoutsuptag:write"}},
 *                   "security_message"="Vous n'avez pas access à cette Ressource"  
 *          }
 *     }
 *  )
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * "denormalizationContext"={"groups"={"postTag:write"}},
 */
class GroupeTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getAllTag:read","postTag:write","getByIdTag:read","getByIdTagGrpTag:read","putajoutsuptag:write","getAllGrpTag:read","postGrTag:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getAllTag:read","postTag:write","getByIdTag:read","getByIdTagGrpTag:read","putajoutsuptag:write","getAllGrpTag:read","postGrTag:write"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags",cascade={"persist"})
     * @Groups({"getAllTag:read","postTag:write","getByIdTag:read","getByIdTagGrpTag:read","putajoutsuptag:write"})
     * ApiSubresource
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
