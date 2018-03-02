<?php
/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 24/02/2018
 * Time: 19:14
 */

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BasketItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BasketItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $userId;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;

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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
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
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param mixed $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * Many BasketItems have One Product.
     * @ManyToOne(targetEntity="App\Entity\Product")
     * @JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BasketItemSelections", mappedBy="basketItem", cascade={"persist"})
     */
    private $selections;

    /**
     * BasketItem constructor.
     */
    public function __construct()
    {
        $this->selections = new ArrayCollection();
    }

    /**
     * @param BasketItemSelections $basketItemSelection
     */
    public function addSelection(BasketItemSelections $basketItemSelection)
    {
        if ($this->selections->contains($basketItemSelection)) {
            return;
        }

        $this->selections[] = $basketItemSelection;
        // set the *owning* side!
        $basketItemSelection->setBasketItem($this);
    }

    /**
     * @param BasketItemSelections $basketItemSelection
     */
    public function removeSelection(BasketItemSelections $basketItemSelection)
    {
        $this->selections->removeElement($basketItemSelection);
        // set the owning side to null
        $basketItemSelection->setBasketItem(null);
    }
    /**
     * @return mixed
     */
    public function getSelections()
    {
        return $this->selections;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BasketTransaction", inversedBy="basketItems")
     * @ORM\JoinColumn(nullable=true)
     */
    private $transaction;

    /**
     * @return BasketTransaction
     */
    public function getBasketTransaction(): ?BasketTransaction
    {
        return $this->transaction;
    }

    /**
     * @param BasketTransaction $transaction
     */
    public function setBasketTransaction(BasketTransaction $transaction = null)
    {
        $this->transaction = $transaction;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        if($this->getDateAdded() == null)
        {
            $this->setDateAdded(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
}