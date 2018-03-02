<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreviewItemRepository")
 */
class PreviewItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $containsAlcohol;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\File(mimeTypes={"image/jpeg", "image/png"})
     */
    private $image;

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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @var string
     */
    private $imageName;

    /**
     * @return mixed
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param mixed $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return mixed
     */
    public function getContainsAlcohol()
    {
        return $this->containsAlcohol;
    }

    /**
     * @param mixed $containsAlcohol
     */
    public function setContainsAlcohol($containsAlcohol): void
    {
        $this->containsAlcohol = $containsAlcohol;
    }




    /**
     * Many PreviewItems have Many Allergies.
     * @ManyToMany(targetEntity="App\Entity\Allergy")
     * @JoinTable(name="preview_allergies",
     *      joinColumns={@JoinColumn(name="preview_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="allergy_id", referencedColumnName="id")}
     *      )
     */
    private $allergies;

    /**
     * PreviewItem constructor.
     */
    public function __construct()
    {
        $this->allergies = new ArrayCollection();
        $this->selections = new ArrayCollection();
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
     * @ORM\OneToMany(targetEntity="App\Entity\BasketItemSelections", mappedBy="previewItem", cascade={"persist"})
     */
    private $selections;


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

}
