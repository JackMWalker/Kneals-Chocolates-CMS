<?php
/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 20/02/2018
 * Time: 20:54
 */

namespace App\Controller;

use App\Entity\Product;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ProductApiController
 * @package App\Controller
 *
 * @Route("/api")
 */
class ProductApiController extends FOSRestController
{
    /**
     * Get products
     * @Rest\Get("/products")
     */
    public function getProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return View::create($products, Response::HTTP_OK);
    }

}