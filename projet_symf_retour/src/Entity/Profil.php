<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiResource(
 *  routePrefix= "/admin",
 *  normalizationContext={"groups"={"profil:read"}},
 *  denormalizationContext={"groups"={"profil:write"}},
 *      itemOperations={"get",
 *          "get_trois"={
 *               "method"="GET",
 *                   "path"="/profils/{id}/users",
 *                   "normalization_context"={"groups"={"get_trois_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *     "get_trois_ad"={
 *               "method"="DELETE",
 *                   "path"="/profils/{id}",
 *                   "normalization_context"={"groups"={"get_trois_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *      "put_pro"={
 *               "method"="PUT",
 *                   "path"="/profils/{id}",
 *                   "denormalization_context"={"groups"={"put_pro:write"}},
 *                   
 *                 
 *          }       
 *     }
 * )
//  * @ApiFilter(BooleanFilter::class, properties={"archivage"})
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil:read","get_trois_ad:read","put_pro:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read","profil:write","get_trois_ad:read","put_pro:write"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource()
     * @Groups({"get_trois_ad:read"})
     */
    private $user;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Groups({"profil:read","profil:write","get_trois_ad:read","put_pro:write"})
     */
    private $archivage;



    public function __construct()
    {
        $this->user = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

   
}
