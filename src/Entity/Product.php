<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
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
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLive;

    /**
     * @ORM\Column(type="float", options={"default":350})
     */
    private $postagePrice;

    /**
     * @ORM\Column(type="float")
     */
    private $postageCost;

    /**
     * @ORM\Column(type="integer", options={"default":5})
     */
    private $stock;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dynamicSelection;

    /**
     * @return mixed
     */
    public function getDynamicSelection()
    {
        return $this->dynamicSelection;
    }

    /**
     * @param mixed $dynamicSelection
     */
    public function setDynamicSelection($dynamicSelection)
    {
        $this->dynamicSelection = $dynamicSelection;
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $dynamicSelectionNumber = null;

    /**
     * @return mixed
     */
    public function getDynamicSelectionNumber()
    {
        return $this->dynamicSelectionNumber;
    }

    /**
     * @param mixed $dynamicSelectionNumber
     */
    public function setDynamicSelectionNumber($dynamicSelectionNumber)
    {
        $this->dynamicSelectionNumber = $dynamicSelectionNumber;
    }

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updatedAt;

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getisLive()
    {
        return $this->isLive;
    }

    /**
     * @param mixed $isLive
     */
    public function setIsLive($isLive)
    {
        $this->isLive = $isLive;
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
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Associations
     */

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductImage", mappedBy="product", cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductOption", mappedBy="product")
     */
    private $options;

    /**
     * Many Products have Many Allergies.
     * @ManyToMany(targetEntity="App\Entity\Allergy")
     * @JoinTable(name="product_allergies",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="allergy_id", referencedColumnName="id")}
     *      )
     */
    private $allergies;

    /**
     * Many Products have One Selection.
     * @ManyToOne(targetEntity="App\Entity\Selection")
     * @JoinColumn(name="selection_id", referencedColumnName="id")
     */
    private $selection;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->allergies = new ArrayCollection();
    }

    /**
     * @param ProductImage $productImage
     */
    public function addImage(ProductImage $productImage)
    {
        if ($this->images->contains($productImage)) {
            return;
        }

        $this->images[] = $productImage;
        // set the *owning* side!
        $productImage->setProduct($this);
    }

    /**
     * @param ProductImage $productImage
     */
    public function removeImage(ProductImage $productImage)
    {
        $this->images->removeElement($productImage);
        // set the owning side to null
        $productImage->setProduct(null);
    }

    /**
     * @return Collection|ProductImage[]
     */
    public function getImages()
    {
        return $this->images;
    }


    /**
     * @param ProductOption $productOption
     */
    public function addOption(ProductOption $productOption)
    {
        if ($this->options->contains($productOption)) {
            return;
        }

        $this->options[] = $productOption;
        // set the *owning* side!
        $productOption->setProduct($this);
    }

    /**
     * @param ProductOption $productOption
     */
    public function removeOption(ProductOption $productOption)
    {
        $this->options->removeElement($productOption);
        // set the owning side to null
        $productOption->setProduct(null);
    }

    /**
     * @return Collection|ProductOption[]
     */
    public function getOptions()
    {
        return $this->options;
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

    /**
     * @return mixed
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * @param $selection
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCreatedAt() == null)
        {
            $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

}
