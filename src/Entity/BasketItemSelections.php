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
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BasketItemSelectionsRepository")
 */
class BasketItemSelections
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\BasketItem", inversedBy="selections")
     * @ORM\JoinColumn(name="basket_item_id", referencedColumnName="id", nullable=false)
     */
    private $basketItem;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\PreviewItem", inversedBy="selections")
     * @ORM\JoinColumn(name="preview_item_id", referencedColumnName="id", nullable=false)
     */
    private $previewItem;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @return mixed
     */
    public function getBasketItem()
    {
        return $this->basketItem;
    }

    /**
     * @param mixed $basketItem
     */
    public function setBasketItem($basketItem): void
    {
        $this->basketItem = $basketItem;
    }

    /**
     * @return mixed
     */
    public function getPreviewItem()
    {
        return $this->previewItem;
    }

    /**
     * @param mixed $previewItem
     */
    public function setPreviewItem($previewItem): void
    {
        $this->previewItem = $previewItem;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }


}