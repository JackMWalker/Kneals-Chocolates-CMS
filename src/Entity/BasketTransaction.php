<?php
/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 25/02/2018
 * Time: 22:31
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BasketTransactionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BasketTransaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $postage;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="string")
     */
    private $uniqid;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPostage()
    {
        return $this->postage;
    }

    /**
     * @param mixed $postage
     */
    public function setPostage($postage): void
    {
        $this->postage = $postage;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return mixed
     */
    public function getUniqid()
    {
        return $this->uniqid;
    }

    /**
     * @param mixed $uniqid
     */
    public function setUniqid($uniqid): void
    {
        $this->uniqid = $uniqid;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BasketItem", mappedBy="transaction", cascade={"persist"})
     */
    private $basketItems;


    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->basketItems = new ArrayCollection();
    }

    /**
     * @param BasketItem $basketItem
     */
    public function addBasketItem(BasketItem $basketItem)
    {
        if ($this->basketItems->contains($basketItem)) {
            return;
        }

        $this->basketItems[] = $basketItem;
        // set the *owning* side!
        $basketItem->setBasketTransaction($this);
    }

    /**
     * @param BasketItem $basketItem
     */
    public function removeBasketItem(BasketItem $basketItem)
    {
        $this->basketItems->removeElement($basketItem);
        // set the owning side to null
        $basketItem->setBasketTransaction(null);
    }

    /**
     * @return ArrayCollection
     */
    public function getBasketItems()
    {
        return $this->basketItems;
    }



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        if($this->getCreatedAt() == null)
        {
            $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
}