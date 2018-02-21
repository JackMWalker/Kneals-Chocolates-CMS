<?php

namespace App\Controller;

use App\Entity\Allergy;
use App\Form\AllergyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class AllergyController extends AbstractController
{

    /**
     * @Route("/allergies", name="allergy_index")
     */
    public function index()
    {
        $allergies = $this->getDoctrine()->getRepository(Allergy::class)->findAll();

        return $this->render('allergy/index.html.twig', array(
            'allergies' => $allergies
        ));
    }

    /**
     * @Route("/allergies/show/{id}", name="allergy_show")
     */
    public function show($id)
    {
        $allergy = $this->getDoctrine()->getRepository(Allergy::class)->find($id);

        return $this->render('allergy/show.html.twig', array(
            'allergy' => $allergy
        ));
    }

    /**
     * @Route("/allergies/add", name="allergy_add")
     */
    public function add(Request $request)
    {
        $allergy = new Allergy();
        $form = $this->createForm(AllergyType::class, $allergy);
        $form->add('submit', SubmitType::class, [
            'label' => 'Create',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($allergy);
            $em->flush();

            $this->addFlash('success', 'New allergy added successfully.');

            return $this->redirectToRoute('allergy_index');
        }


        // replace this line with your own code!
        return $this->render('allergy/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/allergies/edit/{id}", name="allergy_edit")
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $allergy = $em->getRepository(Allergy::class)->find($id);

        if(!$allergy) {
            // Handle a bit better maybe
            throw $this->createNotFoundException(
                'No allergy found for id ' . $id
            );
        }

        $form = $this->createForm(AllergyType::class, $allergy);
        $form->add('submit', SubmitType::class, [
            'label' => 'Edit',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Allergy ID ' . $id . ' updated successfully.');
            return $this->redirectToRoute('allergy_index');
        }

        return $this->render('allergy/edit.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * @Route("/allergies/delete/{id}", name="allergy_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $allergy = $em->getRepository(Allergy::class)->find($id);

        if(!$allergy) {
            // Handle a bit better maybe
            throw $this->createNotFoundException(
                'No allergy found for id ' . $id
            );
        }

        $em->remove($allergy);
        $em->flush();

        $this->addFlash('success', 'Allergy ID ' . $id . ' deleted successfully.');

        return $this->redirectToRoute('allergy_index');
    }
}
