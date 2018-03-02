<?php
/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 24/02/2018
 * Time: 19:41
 */

namespace App\Controller;


use App\Entity\BasketItem;
use App\Entity\BasketItemSelections;
use App\Entity\PreviewItem;
use App\Entity\Product;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class BasketItemApiController
 * @package App\Controller
 *
 * @Route("/api")
 */
class BasketItemApiController extends FOSRestController
{
    /**
     * Get basketItems
     * @Rest\Get("/users/{userId}/basket_items")
     *
     */
    public function getBasket($userId)
    {
        $basketItems = $this->getDoctrine()->getRepository(BasketItem::class)->findPendingByUserId($userId);

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $basketItems
        ], Response::HTTP_OK);
    }

    /**
     * Get basket items count
     * @Rest\Get("/users/{userId}/basket_items/count")
     */

    public function basketCount($userId)
    {
        $basketItems = $this->getDoctrine()->getRepository(BasketItem::class)->findPendingByUserId($userId);

        $count = 0;

        foreach($basketItems as $item) {
            $count += $item->getQuantity();
        }

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $count
        ], Response::HTTP_OK);
    }


    /**
     * Add new item to the basket
     * @Rest\Post("/users/{userId}/basket_items")
     * @param $userId
     * @param Request $request
     * @return View
     */
    public function postBasket($userId, Request $request)
    {
        // Awkward error handling
        // Quantity is required
        if(!$request->request->get('quantity')) {

            return View::create([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request',
                'error' => 'Quantity is required'
            ], Response::HTTP_BAD_REQUEST);
        }
        // productId is required
        if(!$request->request->get('productId')) {

            return View::create([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request',
                'error' => 'Product id is required'
            ], Response::HTTP_BAD_REQUEST);
        }
        // check productId belongs to a real product
        $product = $this->getDoctrine()->getRepository(Product::class)->find($request->request->get('productId'));

        if(!$product) {

            return View::create([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Bad Request',
                'error' => 'Product id doesn\'t match a product'
            ], Response::HTTP_BAD_REQUEST);
        }

        // If there is an item in the basket already belonging to this user with the same product id and is PENDING
        $existingItem = $this->getDoctrine()->getRepository(BasketItem::class)->findPendingByUserIdAndProduct($userId, $request->request->get('productId'));

        $em = $this->getDoctrine()->getManager();
        // If the item is already in the basket, add the quantity on
        if($existingItem) {
            $equal = true;
            // If they have selections, check they are the same too.
            if(sizeof($existingItem->getSelections()) > 0) {

                $existingArray = array();
                // count each preview item to match array_count_values output style
                foreach($existingItem->getSelections() as $selection) {
                    $existingArray[$selection->getPreviewItem()->getId()] = $selection->getAmount();
                }
                // count up the keys in the array
                $selectionIds = array_count_values($request->request->get('selections'));
                // if any counts are different, they aren't equal
                foreach($existingArray as $id => $count) {
                    if(isset($selectionIds[$id]) && $count != $selectionIds[$id]) $equal = false;
                }

                if(sizeof($selectionIds) != sizeof($existingArray)) {
                    $equal = false;
                }
            }

            if($equal) {
                $existingItem->setQuantity($existingItem->getQuantity() + $request->request->get('quantity'));

                $em->persist($existingItem);
                $em->flush();

                return View::create([
                    'code' => Response::HTTP_OK,
                    'message' => 'success',
                    'data' => $existingItem
                ], Response::HTTP_OK);
            }
        }


        // Either create a new basket item or overwrite existing
        $basketItem = new BasketItem();

        // set the rest of the basket vars
        $basketItem->setQuantity($request->request->get('quantity'));
        $basketItem->setUserId($userId);
        $basketItem->setProduct($product);
        // status is not required
        if(!$request->request->get('status'))
            $basketItem->setStatus('PENDING');
        else
            $basketItem->setStatus($request->request->get('status'));

        $em->persist($basketItem);
        $em->flush();

        // If there are selections set
        if($request->request->get('selections')) {
            // GOD SENT FUNCTION
            // returns an array using the values of array as keys and their frequency in array as values
            $selectionIds = array_count_values($request->request->get('selections'));

            foreach($selectionIds as $selectionId => $amount) {
                // Check that the selection id belongs to a real preview item
                $previewItem = $this->getDoctrine()->getRepository(PreviewItem::class)->find($selectionId);
                if(!$previewItem) {
                    return View::create([
                        'code' => Response::HTTP_BAD_REQUEST,
                        'message' => 'Bad Request',
                        'error' => 'Selection id doesn\'t match a selection'
                    ], Response::HTTP_BAD_REQUEST);
                }

                // check that the product actually has this as an option
                if(!$product->hasPreviewItem($previewItem)) {

                    return View::create([
                        'code' => Response::HTTP_BAD_REQUEST,
                        'message' => 'Bad Request',
                        'error' => 'Product doesn\'t have this selection as an option'
                    ], Response::HTTP_BAD_REQUEST);
                }

                $basketItemSelection = new BasketItemSelections();
                $basketItemSelection->setAmount($amount);
                $basketItemSelection->setPreviewItem($previewItem);

                // add selection to basket if all is good
                $basketItem->addSelection($basketItemSelection);
                $em->persist($basketItemSelection);
            }
        }

        // persist
        $em->flush();

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $basketItem
        ], Response::HTTP_OK);
    }


    /**
     * Sets the status of the contents of the basket to REMOVED
     * @Rest\Post("/users/{userId}/basket_items/remove")
     */
    public function removeBasket($userId)
    {
        $existingItems = $this->getDoctrine()->getRepository(BasketItem::class)->findPendingByUserId($userId);

        if(sizeof($existingItems) < 1) {
            return View::create([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Not Found',
                'error' => 'No basket found for this user'
            ], Response::HTTP_NOT_FOUND);
        }

        foreach($existingItems as $existingItem) {
            $existingItem->setStatus('REMOVED');
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $existingItems
        ], Response::HTTP_OK);
    }

    /**
     * Sets the status of the contents of the basket to PURCHASED
     * @Rest\Post("/users/{userId}/basket_items/purchase")
     */
    public function purchasedBasket($userId)
    {
        $existingItems = $this->getDoctrine()->getRepository(BasketItem::class)->findPendingByUserId($userId);

        if(sizeof($existingItems) < 1) {
            return View::create([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Not Found',
                'error' => 'No basket found for this user'
            ], Response::HTTP_NOT_FOUND);
        }

        foreach($existingItems as $existingItem) {
            $existingItem->setStatus('PURCHASED');
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $existingItems
        ], Response::HTTP_OK);
    }


    /**
     * Update a cart item
     * @Rest\Post("/users/{userId}/basket_items/{basketId}")
     */
    public function updateBasket($userId, $basketId, Request $request)
    {
        // If there is an item in the basket already belonging to this user with the same product id and is PENDING
        $existingItem = $this->getDoctrine()->getRepository(BasketItem::class)->findOneBy(array('id' => $basketId, 'userId' => $userId));

        if(!$existingItem) {
            return View::create([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Not Found',
                'error' => 'This item is not in the basket'
            ], Response::HTTP_NOT_FOUND);
        }

        // If quantity is updated
        if($request->request->get('quantity')) {
            $existingItem->setQuantity($request->request->get('quantity'));
        }

        // If status is updated
        if($request->request->get('status')) {
            $existingItem->setStatus($request->request->get('status'));
        }

        // If selection is updated
        if($request->request->get('selections')) {
            // First remove all previous selections
            foreach($existingItem->getSelections() as $selection) {
                $existingItem->removeSelection($selection);
            }
            // Add new selections
            foreach($request->request->get('selections') as $selectionId) {
                // Check that the selection id belongs to a real preview item
                $previewItem = $this->getDoctrine()->getRepository(PreviewItem::class)->find($selectionId);
                if(!$previewItem) {

                    return View::create([
                        'code' => Response::HTTP_NOT_FOUND,
                        'message' => 'Not Found',
                        'error' => 'Selection with this ID is not found'
                    ], Response::HTTP_NOT_FOUND);
                }
                // check that the product actually has this as an option
                if(!$existingItem->getProduct()->hasPreviewItem($previewItem)) {

                    return View::create([
                        'code' => Response::HTTP_BAD_REQUEST,
                        'message' => 'Bad Request',
                        'error' => 'Product doesn\'t have this selection as an option'
                    ], Response::HTTP_BAD_REQUEST);
                }
                // add selection to basket if all is good
                $existingItem->addSelection($previewItem);
            }
        }

        // persist
        $em = $this->getDoctrine()->getManager();
        $em->persist($existingItem);
        $em->flush();

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $existingItem
        ], Response::HTTP_OK);

    }








}