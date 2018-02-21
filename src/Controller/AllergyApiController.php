<?php
/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 19/02/2018
 * Time: 23:02
 */

namespace App\Controller;

use App\Entity\Allergy;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AllergyApiController
 * @package App\Controller
 *
 * @Route("/api")
 */
class AllergyApiController extends FOSRestController
{

    /**
     * Get allergies
     * @Rest\Get("/allergies")
     *
     */
    public function getAllergies()
    {
        $allergies = $this->getDoctrine()->getRepository(Allergy::class)->findAll();

        return View::create($allergies, Response::HTTP_OK);
    }
}