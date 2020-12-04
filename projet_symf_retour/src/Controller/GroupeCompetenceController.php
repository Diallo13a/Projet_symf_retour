<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use App\Repository\GroupeCompetenceRepository;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class GroupeCompetenceController extends AbstractController
{
    /**
     * @Route(
     *      name="addGroupeCompetence" ,
     *      path="/admin/grpecompetences",
     *      methods={"POST"} ,
     *      defaults={
     *         "__controller"="App\Controller\GroupeCompetenceController::addGroupeCompetence",
     *         "_api_resource_class"=GroupeCompetence::class,
     *         "_api_collection_operation_name"="addGroupeCompetence"
     *         }
     *     )
     */
    public function addGroupeCompetence(Request $request){
        //On instancie une nouvelle groupe de competence
        $grpcompetence = new GroupeCompetence();

        //On decode les donnees envoyees
        $donnees = json_decode($request->getContent());

    }

  ///**
   //   * @Route(
  // *      name="getAllGroupeCompetence" ,
  // *      path="/admin/grpecompetences",
  //  *      methods={"GET"} ,
  //  *      defaults={
  // *         "__controller"="App\Controller\GroupeCompetenceController::getAllGroupeCompetence",
  // *         "_api_resource_class"=GroupeCompetence::class,
 //   *         "_api_item_operation_name"="getAlldGroupeCompetence"
  // *         }
  //  *     )
  //  */
   // public function getAlldGroupeCompetence(SerializerInterface $serializer,int $id, GroupeCompetenceRepository $gr_repo){

    //    $grCompe = $gr_repo->apiFindAll($id);

    //    $grCompe = $serializer->normalize($grCompe, 'json', ["groups"=>"get_un_ad:read"]);


    //    return $this->json([
    //        "msg"=>"oki",
     //       "code"=>200,
      //      "result"=>$grCompe
       // ]);


      /*  $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($groupeCompetence, 'json', [
          /*  'circular_reference_handler' => function ($object) {
                return $object->getId();
            }*/
    /*    ]);
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;*/

    //}
    /**
     * @Route(
     *      name="putGroupeCompetence" ,
     *      path="admin/grpecompetences/{id}",
     *      methods={"PUT"} ,
     *      defaults={
     *         "__controller"="App\Controller\GroupeCompetenceController::putGroupeCompetence",
     *         "_api_resource_class"=GroupeCompetence::class,
     *         "_api_collection_operation_name"="putGroupeCompetence"
     *         }
     *     )
     */
    public function putGroupeCompetence(?GroupeCompetence $groupeCompetence, Request $request)
    {
        // On vérifie si la requête est une requête Ajax
       // if($request->isXmlHttpRequest()) {

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());

            // On initialise le code de réponse
            $code = 200;

            // Si l'article n'est pas trouvé
            if(!$groupeCompetence){
                // On instancie un nouvel article
                $article = new GroupeCompetence();
                // On change le code de réponse
                $code = 201;
            }

            // On hydrate l'objet
            $groupeCompetence->setTitre($donnees->libelle);
            $user = $this->getDoctrine()->getRepository(Users::class)->find(1);
            $groupeCompetence->setUsers($user);

            // On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($groupeCompetence);
            $entityManager->flush();

            // On retourne la confirmation
            return new Response('ok', $code);
     //   }
     //   return new Response('Failed', 404);
   }
}
