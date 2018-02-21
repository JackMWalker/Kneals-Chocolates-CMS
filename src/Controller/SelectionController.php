<?php

namespace App\Controller;

use App\Entity\Selection;
use App\Form\SelectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class SelectionController extends AbstractController
{
    /**
     * @Route("/selections", name="selection_index")
     */
    public function index()
    {
        $selections = $this->getDoctrine()->getRepository(Selection::class)->findAll();

        return $this->render('selection/index.html.twig', array(
            'selections' => $selections
        ));
    }

    /**
     * @Route("/selections/show/{id}", name="selection_show")
     */
    public function show($id)
    {
        $selection = $this->getDoctrine()->getRepository(Selection::class)->find($id);

        return $this->render('selection/show.html.twig', array(
            'selection' => $selection
        ));
    }

    /**
     * @Route("/selections/add", name="selection_add")
     */
    public function add(Request $request)
    {
        $selection = new Selection();
        $form = $this->createForm(SelectionType::class, $selection);
        $form->add('submit', SubmitType::class, [
            'label' => 'Create',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($selection);
            $em->flush();

            $this->addFlash('success', 'New selection added successfully.');

            return $this->redirectToRoute('selection_index');
        }


        // replace this line with your own code!
        return $this->render('selection/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/selections/edit/{id}", name="selection_edit")
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $selection = $em->getRepository(Selection::class)->find($id);

        if(!$selection) {
            // Handle a bit better maybe
            throw $this->createNotFoundException(
                'No selection found for id ' . $id
            );
        }

        $form = $this->createForm(SelectionType::class, $selection);
        $form->add('submit', SubmitType::class, [
            'label' => 'Edit',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Selection ID ' . $id . ' updated successfully.');
            return $this->redirectToRoute('selection_index');
        }

        return $this->render('selection/edit.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * @Route("/selections/delete/{id}", name="selection_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $selection = $em->getRepository(Selection::class)->find($id);

        if(!$selection) {
            // Handle a bit better maybe
            throw $this->createNotFoundException(
                'No selection found for id ' . $id
            );
        }

        $em->remove($selection);
        $em->flush();

        $this->addFlash('success', 'Selection ID ' . $id . ' deleted successfully.');

        return $this->redirectToRoute('selection_index');
    }
}
