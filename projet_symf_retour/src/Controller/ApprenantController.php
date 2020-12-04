<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Profil;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Apprenant;

class ApprenantController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userpasswordEncoder;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator,
                                UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->userpasswordEncoder = $userPasswordEncoder;
        $this->entitymanager = $entityManager;
    }

    /**
     * @Route(
     *      name="addApprenant" ,
     *      path="/api/apprenants" ,
     *     methods={"POST"} ,
     *     defaults={
     *         "__controller"="App\Controller\ApprenantController::addApprenant",
     *         "_api_resource_class"=Apprenant::class,
     *         "_api_collection_operation_name"="addApprenant"
     *         }
     *     )
     */

    public function addApprenant(Request $request){
        //all data
        $apprenant = $request->request->all();
//dd($apprenant) ;
        // $profil=$request->request->all()->photo;
       $apprenant = $this->serializer->denormalize($apprenant,"\App\Entity\Apprenant");
        //Recuperation de l'image
        $photo = $request->files->get('photo');


        if(!$photo){
            return new JsonResponse("Veuillez ajouter votre image",Response::HTTP_BAD_REQUEST,[],true);
        }
        $photoBlob = fopen($photo->getRealPath(),"r+");

        $apprenant->setPhoto($photoBlob);


        $errors = $this->validator->validate($apprenant);

       /* if (count($errors)){
             $errors = $this->serializer->serialize($errors,'json');
             return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
         }*/
        $password =  $apprenant->getPassword();
        $apprenant->setPassword($this->userpasswordEncoder->encodePassword($apprenant,$password));
        $apprenant->setArchivage(false);
        $apprenant->setProfil($this->entitymanager->getRepository(Profil::class)->findOneBy(['libelle'=>"APPRENANT"]));

        $em = $this->getDoctrine()->getManager();
        $em->persist($apprenant);
        $em->flush();

        return $this->json("success",201);
    }



}
