<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;

class UserController extends AbstractController
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

    /**
     * UserController constructor.
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator,UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->userpasswordEncoder = $userPasswordEncoder;
    }
    /**
    *  @Route(
    *      name="addUser" ,
    *      path="/api/admin/users" ,
    *     methods={"POST"} ,
    *     defaults={
    *         "__controller"="App\Controller\UserController::addUser",
    *         "_api_resource_class"=User::class,
    *         "_api_collection_operation_name"="adding"
    *         }
    *     )
    *  */

    public function addUser(Request $request, UserRepository $repository){
        //all data
        $user = $request->request->all();
//dd($user) ;
       // $profil=$request->request->all()->photo;
        //Recuperation de l'image
        $photo = $request->files->get('photo');

        $user = $this->serializer->denormalize($user,"\App\Entity\User",true);
        if(!$photo){
            return new JsonResponse("Veuillez ajouter votre image",Response::HTTP_BAD_REQUEST,[],true);
        }
        $photoBlob = fopen($photo->getRealPath(),"rb");

        $user->setPhoto($photoBlob);


        $errors = $this->validator->validate($user);

        if (count($errors)){
            $errors = $this->serializer->serialize($errors,'json');
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $password =  $user->getPassword();
        $user->setPassword($this->userpasswordEncoder->encodePassword($user,$password));
        $user->setArchivage(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json("success",201);
    }

    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
