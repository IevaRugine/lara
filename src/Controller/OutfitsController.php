<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Outfits;
use App\Entity\Masters;

class OutfitsController extends AbstractController
{
    /**
     * @Route("/outfits", name="outfits_index")
     */
    public function index(Request $r): Response
    {
        // $outfits = $this->getDoctrine()
        //     ->getRepository(Outfits::class)
        //     ->findAll();

        $masters = $this->getDoctrine()
            ->getRepository(Masters::class)
            ->findAll();


        $outfits = $this->getDoctrine()
            ->getRepository(Outfits::class);

        if ('0' == $r->query->get('master_id')) {
            $outfits = $outfits->findAll();
        } elseif (null !== $r->query->get('master_id')) {
            $outfits = $outfits->findBy(['master_id' => $r->query->get('master_id')]);
        } else {
            $outfits = $outfits->findAll();
        }

        return $this->render('outfits/index.html.twig', [
            'outfits' => $outfits,
            'masters' => $masters,
            'masterId' => $r->query->get('master_id') ?? 0
        ]);
    }

    /**
     * @Route("/outfits/create", name="outfits_create", methods={"GET"})
     */
    public function create(): Response
    {

        $masters = $this->getDoctrine()
            ->getRepository(Masters::class)
            ->findAll();


        return $this->render('outfits/create.html.twig', [
            'masters' => $masters,
        ]);
    }


    /**
     * @Route("/outfits/store", name="outfits_store", methods={"POST"})
     */
    public function store(Request $r): Response
    {
        $master = $this->getDoctrine()
            ->getRepository(Masters::class)
            ->find($r->request->get('outfits_master_id'));

        $outfit = new Outfits;
        $outfit
            ->setType($r->request->get('outfit_type'))
            ->setColor($r->request->get('outfit_color'))
            ->setSize($r->request->get('outfit_size'))
            ->setAbout($r->request->get('outfit_about'))
            ->setMaster($master);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($outfit);
        $entityManager->flush();

        return $this->redirectToRoute('outfits_index');
    }

    /**
     * @Route("/outfits/edit/{id}", name="outfits_edit", methods={"GET"})
     */
    public function edit(int $id): Response
    {

        $outfit = $this->getDoctrine()
            ->getRepository(Outfits::class)
            ->find($id);

        $masters = $this->getDoctrine()
            ->getRepository(Masters::class)
            ->findAll();

        return $this->render('outfits/edit.html.twig', [
            'outfit' => $outfit,
            'masters' => $masters,
        ]);
    }

    /**
     * @Route("/outfits/update/{id}", name="outfits_update", methods={"POST"})
     */
    public function update(Request $r, $id): Response
    {

        $outfit = $this->getDoctrine()
            ->getRepository(Outfits::class)
            ->find($id);

        $master = $this->getDoctrine()
            ->getRepository(Masters::class)
            ->find($r->request->get('outfits_master'));

        $outfit
            ->setType($r->request->get('outfit_type'))
            ->setColor($r->request->get('outfit_color'))
            ->setSize($r->request->get('outfit_size'))
            ->setAbout($r->request->get('outfit_about'))
            ->setMaster($master);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($outfit);
        $entityManager->flush();

        return $this->redirectToRoute('outfits_index');
    }

    /**
     * @Route("/outfits/delete/{id}", name="outfits_delete", methods={"POST"})
     */
    public function delete($id): Response
    {
        $outfit = $this->getDoctrine()
            ->getRepository(Outfits::class)
            ->find($id);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($outfit);
        $entityManager->flush();

        return $this->redirectToRoute('outfits_index');
    }
}
