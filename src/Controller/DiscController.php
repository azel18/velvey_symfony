<?php

namespace App\Controller;

use App\Entity\Disc;
use App\Form\Disc1Type;
use App\Repository\DiscRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/disc')]
class DiscController extends AbstractController
{
    #[Route('/', name: 'app_disc_index', methods: ['GET'])]
    public function index(DiscRepository $discRepository): Response
    {
        return $this->render('disc/index.html.twig', [
            'discs' => $discRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_disc_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DiscRepository $discRepository): Response
    {
        $disc = new Disc();
        $form = $this->createForm(Disc1Type::class, $disc);
        $form->handleRequest($request);
        $imgdisc = $form["title"]->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            // récupération de la saisi sur l'upload
            $pictureFile = $form['picture2']->getData();
            // vérification s'il y a un upload photo
            if ($pictureFile) {
                // renommage du fichier
                // nom du fichier + extension
                $newPicture = $imgdisc . '.' . $pictureFile->guessExtension();
                // assignation de la valeur à la propriété picture à l'aide du setter
                $disc->setPicture($newPicture);
                try {
                    // déplacement du fichier vers le répertoire de destination sur le serveur
                    $pictureFile->move(
                        $this->getParameter('photo_directory'),
                        $newPicture
                    );
                } catch (FileException $e) {
                    // gestion de l'erreur si le déplacement ne s'est pas effectué
                }
            }

            $discRepository->save($disc, true);

            return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disc/new.html.twig', [
            'disc' => $disc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disc_show', methods: ['GET'])]
    public function show(Disc $disc): Response
    {
        return $this->render('disc/show.html.twig', [
            'disc' => $disc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_disc_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Disc $disc, DiscRepository $discRepository): Response
    {
        $iddisc = $disc->getId();
        $form = $this->createForm(Disc1Type::class, $disc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // récupération de la saisi sur l'upload
            $pictureFile = $form['picture2']->getData();
            // vérification s'il y a un upload photo
            if ($pictureFile) {
                // renommage du fichier
                // nom du fichier + extension
                $newPicture = $iddisc . '.' . $pictureFile->guessExtension();
                // assignation de la valeur à la propriété picture à l'aide du setter
                $disc->setPicture($newPicture);
                try {
                    // déplacement du fichier vers le répertoire de destination sur le serveur
                    $pictureFile->move(
                        $this->getParameter('photo_directory'),
                        $newPicture
                    );
                } catch (FileException $e) {
                    // gestion de l'erreur si le déplacement ne s'est pas effectué
                }
            }
            $discRepository->save($disc, true);

            return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disc/edit.html.twig', [
            'disc' => $disc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disc_delete', methods: ['POST'])]
    public function delete(Request $request, Disc $disc, DiscRepository $discRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $disc->getId(), $request->request->get('_token'))) {
            $discRepository->remove($disc, true);
        }

        return $this->redirectToRoute('app_disc_index', [], Response::HTTP_SEE_OTHER);
    }
}
