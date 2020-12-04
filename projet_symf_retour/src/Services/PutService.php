<?php


namespace App\Services;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PutService
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var EntityManagerInterface
     */
    private $entitymanager;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userpasswordencoder;
    /**
     * @var UserRepository
     */
    private $userrepository;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager,
                                ValidatorInterface $validator, UserPasswordEncoderInterface $userPasswordEncoder,
                                UserRepository $userRepository){
        $this->serializer=$serializer;
        $this->entitymanager=$entityManager;
        $this->validator=$validator;
        $this->userpasswordencoder=$userPasswordEncoder;
        $this->userrepository=$userRepository;
    }
    public function putData($request,$id){

        $dataId=$this->userrepository->find($id);
        $data=$request->request->all();
        foreach ($data as $key=>$value){
            if ($key!=="_method" || !$value){
                $dataId->{"set".ucfirst($key)}($value);
            }
        }
        $photo=$request->files->get("photo");
        $photoBlob = fopen($photo->getRealpath(),"rb");
        if ($photo){
            $dataId->setPhoto($photoBlob);
        }
        $errors=$this->validator->validate($dataId);
        if ($errors){
            $errors=$this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $this->entitymanager->persist($dataId);
        $this->entitymanager->flush();
        return new JsonResponse("success",201);
    }

}