<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductOptionRepository")
 */
class ProductOption
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", options={"default":350})
     */
    private $postagePrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $postageCost;

    /**
     * @ORM\Column(type="integer", options={"default":5})
     */
    private $stock;

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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getPostagePrice()
    {
        return $this->postagePrice;
    }

    /**
     * @param mixed $postagePrice
     */
    public function setPostagePrice($postagePrice)
    {
        $this->postagePrice = $postagePrice;
    }

    /**
     * @return mixed
     */
    public function getPostageCost()
    {
        return $this->postageCost;
    }

    /**
     * @param mixed $postageCost
     */
    public function setPostageCost($postageCost)
    {
        $this->postageCost = $postageCost;
    }

    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="options")
     * @ORM\JoinColumn(nullable=true)
     */
    private $product;

    /**
     * @return Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;
    }

    /**
     * Many ProductOptions have Many Allergies.
     * @ManyToMany(targetEntity="App\Entity\Allergy")
     * @JoinTable(name="option_allergies",
     *      joinColumns={@JoinColumn(name="option_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="allergy_id", referencedColumnName="id")}
     *      )
     */
    private $allergies;

    public function __construct()
    {
        $this->allergies = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getAllergies()
    {
        return $this->allergies;
    }

    /**
     * @param Allergy $allergy
     */
    public function addAllergy(Allergy $allergy)
    {
        if ($this->allergies->contains($allergy)) {
            return;
        }

        $this->allergies[] = $allergy;
    }

    /**
     * @param Allergy $allergy
     */
    public function removeAllergy(Allergy $allergy)
    {
        $this->allergies->removeElement($allergy);
    }
}
