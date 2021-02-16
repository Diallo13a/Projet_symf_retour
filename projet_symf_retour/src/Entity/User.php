<?php

namespace App\Entity;

//use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"admin"="Admin","apprenant"="Apprenant", "cm"="Cm" , "formateur"="Formateur" ,"user"="User"})
 * @ApiResource(routePrefix= "/admin",
 *  normalizationContext={"groups"={"user:read"}},
 *  denormalizationContext={"groups"={"user:write"}},
 *     collectionOperations={
 *           "get_un"={
 *               "method"="GET",
 *                   "path"="/users",
 *                   "normalization_context"={"groups"={"get_un_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "adding"={
 *              "route_name"="addUser" ,
 *              "method"="POST",
 *               "deserialize"=false
 *           }
 *     })
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getPrRfApFr:read","getApp:read","postAppForm:write","getByIdApp:read","putApp:write","delAppGrp:write","get_un_ad:read","get_trois_ad:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:write","user:read","get_un_ad:read","get_deux_ad:read","get_trois_ad:read","getPrRfApFr:read","getApp:read","postAppForm:write","getByIdApp:read","putApp:write","delAppGrp:write","get_trois_ad:read"})
     * @Assert\NotBlank(message="Cet utilisateur")
     */
    private $username;

    
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="user")
     * @Groups({"user:write","user:read"})
     */
    private $profil;

    /**
     * @ORM\Column(type="blob",nullable=true)
     * @Groups({"user:write","get_un_ad:read","user:read","get_trois_ad:read"})
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\Email(
     *     message = "E-mail invalide."
     * )
     * @Groups({"user:write","user:read","get_trois_ad:read","getPrRfApFr:read","getApp:read","get_un_ad:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"getPrRfApFr:read"})
     */
    private $archivage=0;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:write","user:read","get_trois_ad:read","getPrRfApFr:read","getApp:read","get_un_ad:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:write","user:read","get_trois_ad:read","getPrRfApFr:read","getApp:read","get_un_ad:read"})
     */
    private $prenom;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getPhoto()
    {
        $photo = $this->photo;
        if (!empty($photo))
        {
            return (base64_encode(stream_get_contents($photo)));
        }
        return $photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(?bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
    
}
