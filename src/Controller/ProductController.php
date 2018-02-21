<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * @Route("/products", name="product_index")
     */
    public function index()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', array(
            'products' => $products
        ));
    }

    /**
     * @Route("/products/show/{id}", name="product_show")
     */
    public function show($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        return $this->render('product/show.html.twig', array(
            'product' => $product
        ));
    }

    /**
     * @Route("/products/add", name="product_add")
     */
    public function add(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->add('submit', SubmitType::class, [
            'label' => 'Create',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'New product added successfully.');

            return $this->redirectToRoute('product_index');
        }


        // replace this line with your own code!
        return $this->render('product/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/products/edit/{id}", name="product_edit")
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        if(!$product) {
            // Handle a bit better maybe
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->add('submit', SubmitType::class, [
            'label' => 'Edit',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            foreach($product->getImages() as $image) {
                if(!$image->getImage()) {
                    $image->setImage($image->getImageName());
                }
            }

            $em->flush();

            $this->addFlash('success', 'Product ID ' . $id . ' updated successfully.');
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', array(
            'form' => $form->createView(),
            'product' => $product
        ));
    }

    /**
     * @Route("/products/delete/{id}", name="product_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        if(!$product) {
            // Handle a bit better maybe
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Product ID ' . $id . ' deleted successfully.');

        return $this->redirectToRoute('product_index');
    }
}
