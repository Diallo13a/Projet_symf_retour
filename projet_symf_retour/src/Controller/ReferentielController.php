<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ReferentielController extends AbstractController
{
    /**
     * @Route("/referentiel", name="referentiel")
     */
    public function index(): Response
    {
        return $this->render('referentiel/index.html.twig', [
            'controller_name' => 'ReferentielController',
        ]);
    }




    /**
     * @Route(
     *     name="addReferentiel",
     *     path="/api/admin/referentiels",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::addReferentiel",
     *          "__api_resource_class"="App\Entity\Referentiel::class",
     *          "__api_collection_operation_name"="addReferentiel"
     *     }
     * )
     */
    public function addReferentiel(Request $request, EntityManagerInterface $manager,
     GroupeCompetenceRepository $groupeCompetenceRepository, SerializerInterface $serializer,
     DenormalizerInterface $denormalizer) {
        $referentielAdded = $request->request->all();

        $referentiel = $serializer->denormalize($referentielAdded, "App\Entity\Referentiel");
       // dd($referentielAdded['libelle']);
     
        // get file if exist
        $programme = $request->files->get('programme');
        if ($programme) {
            $getRealpathprogramme = $programme->getRealPath();  
            //dd($getRealpathprogramme);
            $openProgramme = fopen($getRealpathprogramme, 'r+');
           // dd($openProgramme);
            $referentiel->setProgramme($openProgramme);
            // dd($referentiel);
        }
        
         // get file if groupeCompetence exist
        if ($referentielAdded['grpe']){
            $AllgroupeCompetence = explode (',', $referentielAdded['grpe']);
            // dd($referentielAdded);
            for ($i=0; $i < count($AllgroupeCompetence); $i++) {
                
                if ($groupeCompetenceRepository->findOneBy(['id'=>(int)$AllgroupeCompetence[$i]])) {
                    
                    $referentielAdded = $referentiel->addGroupecompetence($groupeCompetenceRepository->findOneBy(['id'=>(int)$AllgroupeCompetence[$i]]));
                   
                }
            }
        }

        //dd($referentielAdded);
        $manager->persist($referentiel);
        $manager->flush();
        return $this->json("success", 201);
    }
}
