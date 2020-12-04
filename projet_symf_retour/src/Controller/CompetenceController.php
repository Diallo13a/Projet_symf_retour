<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competence;
use Symfony\Component\Serializer\SerializerInterface;

class CompetenceController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager){

        $this->entitymanager=$entityManager;
    }
    /**
     * @Route(
     *      name="putCompetencepath" ,
     *      path="/api/admin/competences/{id}",
     *      methods={"PUT"}
     *     )
     */
    public function putCompetence(Request $request,$id,SerializerInterface $serializer)
    {
        //dd("salut");
        $donnees=json_decode($request->getContent(),true);
        $donnees['id']="/api/admin/competences/".$id;
        foreach($donnees['niveaux'] as $key => $niveau)
        {
            $donnees["niveaux"][$key]["id"]="/api/admin/niveaux/".$niveau['id'];

        }
        $objeCompt= $serializer->denormalize($donnees,"App\Entity\Competence",true);
        $objeCompt->setId($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($objeCompt);
        $entityManager->flush();

        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);


    }
}
