<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Masters;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MastersController extends AbstractController
{
    /**
     * @Route("/masters", name="masters_index", methods={"GET"}))
     */
    public function index(Request $r): Response

    {
        // $masters = $this->getDoctrine()
        //     ->getRepository(Masters::class)
        //     ->findAll();


        $masters = $this->getDoctrine()
            ->getRepository(Masters::class);

        if ('name_az' == $r->query->get('sort')) {
            $masters = $masters->findBy([], ['name' => 'asc']);
        } elseif ('name_za' == $r->query->get('sort')) {
            $masters = $masters->findBy([], ['name' => 'desc']);
        } elseif ('surname_az' == $r->query->get('sort')) {
            $masters = $masters->findBy([], ['surname' => 'asc']);
        } elseif ('surname_az' == $r->query->get('sort')) {
            $masters = $masters->findBy([], ['surname' => 'desc']);
        } else {
            $masters =  $masters->findAll();
        }

        return $this->render('masters/index.html.twig', [
            'masters' => $masters,
            'sortBy' => $r->query->get('sort') ?? 'default'
        ]);
    }


    /**
     * @Route("/masters/create", name="masters_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        return $this->render('masters/create.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', [])
        ]);
    
    }

    /**
     * @Route("/masters/store", name="masters_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $master = new Masters;
        $master->setName($r->request->get('master_name'))->setSurname($r->request->get('master_surname'));

        $errors = $validator->validate($master);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('masters_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($master);
        $entityManager->flush();

        return $this->redirectToRoute('masters_index');
    }

    /**
     * @Route("/masters/edit/{id}", name="masters_edit", methods={"GET"})
     */
    public function edit(int $id): Response
    {
        $master = $this->getDoctrine()
            ->getRepository(Masters::class)
            ->find($id);

        return $this->render('masters/edit.html.twig', [
            'master' => $master,
        ]);
    }

    /**
     * @Route("/masters/update/{id}", name="masters_update", methods={"POST"})
     */
    public function update(Request $r, $id): Response
    {
        $master = $this->getDoctrine()
            ->getRepository(Masters::class)
            ->find($id);

        $master->setName($r->request->get('master_name'))->setSurname($r->request->get('master_surname'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($master);
        $entityManager->flush();

        return $this->redirectToRoute('masters_index');
    }

    /**
     * @Route("/masters/delete/{id}", name="masters_delete", methods={"POST"})
     */
    public function delete($id): Response
    {
        $master = $this->getDoctrine()
            ->getRepository(Masters::class)
            ->find($id);

        //dd($master->getBooks()->count());

        if ($master->getOutfits()->count() > 0) {
            return new Response('Access to delete denied, since this master has available outfits');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($master);
        $entityManager->flush();

        return $this->redirectToRoute('masters_index');
    }
}
