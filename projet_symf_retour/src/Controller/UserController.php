<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Apprenant;

use App\Services\PutService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator,UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $manager)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->userpasswordEncoder = $userPasswordEncoder;
        $this->manager = $manager;
    }
    /**
    *  @Route(
    *      name="addUser" ,
    *      path="/api/admin/users" ,
    *      methods={"POST"} ,
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

        // $profil = $user["profils"] ;
        //  if($profil == "ADMIN") {
        //      $user = $this->serializer->denormalize($user, "App\Entity\Admin");
        // } elseif ($profil =="APPRENANT") {
        //      $user = $this->serializer->denormalize($user, "App\Entity\Apprenant");
        // } elseif ($profil =="FORMATEUR") {
        //      $user = $this->serializer->denormalize($user, "App\Entity\Formateur");
        // }elseif ($profil =="CM") {
        //      $user = $this->serializer->denormalize($user, "App\Entity\Cm");
        // }


        $profil="App\\Entity\\".trim($user["type"]);
        //dd(class_exists($profil)) ;
        if (class_exists($profil)){
            $user = $this->serializer->denormalize($user,$profil,true);
            if($photo){
               // return new JsonResponse("Veuillez ajouter votre image",Response::HTTP_BAD_REQUEST,[],true);
               $photoBlob = fopen($photo->getRealPath(),"rb");

            $user->setPhoto($photoBlob);
               
            }
            


            $errors = $this->validator->validate($user);

            /* if (count($errors)){
                 $errors = $this->serializer->serialize($errors,'json');
                 return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
             }*/
            $password =  $user->getPassword();
            $user->setPassword($this->userpasswordEncoder->encodePassword($user,$password));
            $user->setArchivage(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);

            // dd($user);
            $em->flush();

            return $this->json("success",201);
        }

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

// public function addUser( Request $request) {

//     //all data
//     $user = $request->request->all() ;

//     //get profil
//     $profil = $user["profils"] ;
    
//      if($profil == "ADMIN") {
//          $user = $this->serialize->denormalize($user, "App\Entity\Admin");
//     } elseif ($profil =="APPRENANT") {
//          $user = $this->serialize->denormalize($user, "App\Entity\Apprenant");
//     } elseif ($profil =="FORMATEUR") {
//          $user = $this->serialize->denormalize($user, "App\Entity\Formateur");
//     }elseif ($profil =="CM") {
//          $user = $this->serialize->denormalize($user, "App\Entity\Cm");
//          dd($user);
//     }
//     //recupération de l'image
//     $photo = $request->files->get("photo");
//     //is not obliged
//     if($photo)
//     {
//         //  return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
//         //$base64 = base64_decode($imagedata);
//         $photoBlob = fopen($photo->getRealPath(),"rb");

//         $user->setPhoto($photoBlob);
//     }


//     $errors = $this->validator->validate($user);
//     // if (count($errors)){
//     //     $errors = $this->serialize->serialize($errors,"json");
//     //     return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
//     // }
//     // dd($user);
//     // $password =  $user->getPassword();
//     // $user->setPassword($this->userpasswordEncoder->encodePassword($user,$password));
//     // $user->setArchivage(false);

// dd($user);
//     $user->setProfil($this->manager->getRepository(Profil::class)->findOneBy(['libelle'=>$profil])) ;

//     $em = $this->getDoctrine()->getManager();
//     $em->persist($user);
//     $em->flush();

//     return $this->json("success",201);

// }

    // /**
    //  * @Route(
    //  *      name="updated" ,
    //  *      path="/api/admin/users/{id}" ,
    //  *       methods={"PUT"}
    //  *),
    //  * @Route(
    //  *      name="UpdatedApprenant" ,
    //  *      path="/api/apprenants/{id}" ,
    //  *      methods={"PUT"}
    //  *)
    //  */
    // public function cool(Request $request , PutService $putService,$id) {

    //     return  $putService->putData($request, $id) ;

    // }



    /**
     * @Route(
     *      name="updated" ,
     *      path="/api/admin/users/{id}" ,
     *       methods={"PUT"}
     *)
     */
    public function putUser(Request $request, PutService $postService, 
    EntityManagerInterface $manager,SerializerInterface $serializer,UserRepository $u, $id) {
        $userForm= $postService->UpdateUser($request, 'photo');
        //dd($userForm);
         $user = $u->find($id);
         foreach ($userForm as $key => $value) {
             if($key === 'profils'){
                 $value = $serializer->denormalize($value, Profil::class);
             }
             $setter = 'set'.ucfirst(trim(strtolower($key)));
             //dd($setter);
             if(method_exists(User::class, $setter)) {
                 $user->$setter($value);
                 //dd($user);
             }
         }
         $manager->flush();

         return new JsonResponse("success",200) ;
        //  return new JsonResponse("success",200,[],true);
 
     }

}
