<?php
/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 20/02/2018
 * Time: 20:54
 */

namespace App\Controller;

use App\Entity\BasketItem;
use App\Entity\BasketTransaction;
use App\Entity\PaymentPaypalTransaction;
use App\Entity\Product;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class TransactionApiController
 * @package App\Controller
 *
 * @Route("/api")
 */
class TransactionApiController extends FOSRestController
{
    /**
     * Create new basket transaction
     * @Rest\Post("/transactions/basket")
     */
    public function createBasketTransaction(Request $request)
    {
        // check if all fields are entered
        if(!$request->request->get('price') || !$request->request->get('postage')
            || !$request->request->get('total_price') || !$request->request->get('uniqid')
            || !$request->request->get('status') || !$request->request->get('basket_items')) {

            return View::create([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Parameter Missing',
                'error' => ''
            ], Response::HTTP_BAD_REQUEST);
        }

        $basketTransaction = new BasketTransaction();

        foreach($request->request->get('basket_items') as $basketId) {
            $basketItem = $this->getDoctrine()->getRepository(BasketItem::class)->find($basketId);

            if(!$basketItem) {
                return View::create([
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => 'Not Found',
                    'error' => 'Basket item id: '.$basketItem. ' not found'
                ], Response::HTTP_NOT_FOUND);
            }
            if($basketItem->getStatus() != 'PENDING') {
                return View::create([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Bad Request',
                    'error' => 'Basket item id: '.$basketItem. ' must be PENDING'
                ], Response::HTTP_BAD_REQUEST);
            }
            else {

                $basketTransaction->addBasketItem($basketItem);
            }
        }

        $basketTransaction->setPrice($request->request->get('price'));
        $basketTransaction->setPostage($request->request->get('postage'));
        $basketTransaction->setTotalPrice($request->request->get('total_price'));
        $basketTransaction->setUniqid($request->request->get('uniqid'));
        $basketTransaction->setStatus($request->request->get('status'));

        $em = $this->getDoctrine()->getManager();

        $em->persist($basketTransaction);
        $em->flush();

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $basketTransaction
        ], Response::HTTP_OK);
    }

    /**
     * Update basket transaction
     * @Rest\Post("/transactions/basket/{basketTransactionId}")
     */
    public function updateBasketTransaction($basketTransactionId, Request $request)
    {
        $basketTransaction = $this->getDoctrine()->getRepository(BasketTransaction::class)->find($basketTransactionId);

        if(!$basketTransaction) {
            return View::create([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Not Found',
                'error' => 'Basket transaction id: '.$basketTransactionId. ' not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if($status = $request->request->get('status')) {
            $basketTransaction->setStatus($status);
        }

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $basketTransaction
        ], Response::HTTP_OK);
    }

    /**
     * Get a basket transaction
     * @Rest\Get("/transactions/basket")
     */
    public function getBasketTransaction(Request $request)
    {
        $transaction = null;

        if($uniqid = $request->query->get('uniqid')) {
            $transaction = $this->getDoctrine()->getRepository(BasketTransaction::class)->findOneBy(array('uniqid' => $uniqid));
        }

        if(!$transaction) {
            return View::create([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Not found',
                'error' => 'Couldn\'t find a transaction with the given parameters'
            ], Response::HTTP_NOT_FOUND);
        }

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $transaction
        ], Response::HTTP_OK);
    }


    /**
     * Get a basket transaction
     * @Rest\Get("/transactions/paypal")
     */
    public function getPaypalTransaction(Request $request)
    {
        $transaction = null;

        if($paymentId = $request->query->get('payment_id')) {
            $transaction = $this->getDoctrine()->getRepository(PaymentPaypalTransaction::class)->findOneBy(array('paymentId' => $paymentId));
        }

        if(!$transaction) {
            return View::create([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Not found',
                'error' => 'Couldn\'t find a paypal transaction with the given parameters'
            ], Response::HTTP_NOT_FOUND);
        }

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $transaction
        ], Response::HTTP_OK);
    }

    /**
     * Create a new Paypal transaction
     * @Rest\Post("/transactions/paypal")
     */
    public function createPaypalTransaction(Request $request)
    {
        // check if all fields are entered
        if(!$request->request->get('payer_name') || !$request->request->get('address_line1')
            || !$request->request->get('city') || !$request->request->get('postal_code')
            || !$request->request->get('payer_id') || !$request->request->get('payer_email')
            || !$request->request->get('payment_status') || !$request->request->get('transaction_id')
            || !$request->request->get('payment_id') || !$request->request->get('price')) {

            return View::create([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'Parameter Missing',
                'error' => ''
            ], Response::HTTP_BAD_REQUEST);
        }

        $transactionId = $request->request->get('transaction_id');

        $basketTransaction = $this->getDoctrine()->getRepository(BasketTransaction::class)->find($transactionId);

        if(!$basketTransaction) {
            return View::create([
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'Not Found',
                'error' => 'Basket transaction with id: '.$transactionId.' is not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $paypalTransaction = new PaymentPaypalTransaction();

        $paypalTransaction->setPrice($request->request->get('price'));
        $paypalTransaction->setAddressLine1($request->request->get('address_line1'));
        if($address2 = $request->request->get('address_line2')) {
            $paypalTransaction->setAddressLine2($request->request->get('address_line2'));
        }
        else {
            $paypalTransaction->setAddressLine2("");
        }
        $paypalTransaction->setCity($request->request->get('city'));
        $paypalTransaction->setPostalCode($request->request->get('postal_code'));
        $paypalTransaction->setPayerName($request->request->get('payer_name'));
        $paypalTransaction->setPayerId($request->request->get('payer_id'));
        $paypalTransaction->setEmail($request->request->get('payer_email'));
        $paypalTransaction->setPaymentStatus($request->request->get('payment_status'));
        $paypalTransaction->setPaymentId($request->request->get('payment_id'));

        if($fee = $request->request->get('fee')) {
            $paypalTransaction->setFee($fee);
        }
        else {
            $paypalTransaction->setFee(0);
        }

        $paypalTransaction->setTransaction($basketTransaction);

        $em = $this->getDoctrine()->getManager();

        $em->persist($paypalTransaction);
        $em->flush();

        return View::create([
            'code' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $paypalTransaction
        ], Response::HTTP_OK);
    }

}