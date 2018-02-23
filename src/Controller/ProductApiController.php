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

        $map = function($p) {
            $p->setPrice(number_format($p->getPrice(), 2, '.', ','));
            $p->setCost(number_format($p->getCost(), 2, '.', ','));
            $p->setPostagePrice(number_format($p->getPostagePrice(), 2, '.', ','));
            $p->setPostageCost(number_format($p->getPostageCost(), 2, '.', ','));
            return $p;
        };

        $filteredArray = array_map($map, $products);

        return View::create($filteredArray, Response::HTTP_OK);
    }

}