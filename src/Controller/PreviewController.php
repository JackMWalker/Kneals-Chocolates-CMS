<?php

namespace App\Controller;

use App\Entity\PreviewItem;
use App\Form\PreviewItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class PreviewController extends Controller
{
    /**
     * @Route("/previews", name="preview_index")
     */
    public function index()
    {
        $previews = $this->getDoctrine()->getRepository(PreviewItem::class)->findAll();

        return $this->render('preview/index.html.twig', array(
            'previews' => $previews
        ));
    }

    /**
     * @Route("/previews/show/{id}", name="preview_show")
     */
    public function show($id)
    {
        $preview = $this->getDoctrine()->getRepository(PreviewItem::class)->find($id);

        return $this->render('preview/show.html.twig', array(
            'preview' => $preview
        ));
    }

    /**
     * @Route("/previews/add", name="preview_add")
     */
    public function add(Request $request)
    {
        $preview = new PreviewItem();
        $form = $this->createForm(PreviewItemType::class, $preview);
        $form->add('submit', SubmitType::class, [
            'label' => 'Create',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($preview);
            $em->flush();

            $this->addFlash('success', 'New preview added successfully.');

            return $this->redirectToRoute('preview_index');
        }


        // replace this line with your own code!
        return $this->render('preview/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/previews/edit/{id}", name="preview_edit")
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $preview = $em->getRepository(PreviewItem::class)->find($id);

        if(!$preview) {
            // Handle a bit better maybe
            throw $this->createNotFoundException(
                'No preview found for id ' . $id
            );
        }

        $form = $this->createForm(PreviewItemType::class, $preview);
        $form->add('submit', SubmitType::class, [
            'label' => 'Edit',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(!$preview->getImage()) {
                $preview->setImage($preview->getImageName());
            }

            $em->flush();

            $this->addFlash('success', 'Preview ID ' . $id . ' updated successfully.');
            return $this->redirectToRoute('preview_index');
        }

        return $this->render('preview/edit.html.twig', array(
            'form' => $form->createView(),
            'preview' => $preview
        ));
    }

    /**
     * @Route("/previews/delete/{id}", name="preview_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $preview = $em->getRepository(PreviewItem::class)->find($id);

        if(!$preview) {
            // Handle a bit better maybe
            throw $this->createNotFoundException(
                'No preview found for id ' . $id
            );
        }

        $em->remove($preview);
        $em->flush();

        $this->addFlash('success', 'Preview ID ' . $id . ' deleted successfully.');

        return $this->redirectToRoute('preview_index');
    }
}
