<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *        
 *          "post_un"={
 *               "method"="POST",
 *                   "path"="admin/users",
 *                   "normalization_context"={"groups"={"post_un_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 *        itemOperations={
 *
 *           "get_deux"={
 *               "method"="GET",
 *                   "path"="admin/users/id",
 *                   "normalization_context"={"groups"={"get_deux_ad:read"}},
 *                   "security"="is_granted('ROLE_ADMIN')",
 *                   "security_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *
 *
 *}
 * )
 */
class Admin extends User
{
    
    
}
