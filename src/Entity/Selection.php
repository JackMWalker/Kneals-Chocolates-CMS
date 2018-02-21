<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SelectionRepository")
 */
class Selection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    private $name;


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
     * Many Selections have Many PreviewItems.
     * @ManyToMany(targetEntity="App\Entity\PreviewItem")
     * @JoinTable(name="selection_items",
     *      joinColumns={@JoinColumn(name="selection_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="preview_id", referencedColumnName="id")}
     *      )
     */
    private $previewItems;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->previewItems = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPreviewItems()
    {
        return $this->previewItems;
    }

    /**
     * @param PreviewItem $previewItem
     */
    public function addPreviewItem(PreviewItem $previewItem)
    {
        if ($this->previewItems->contains($previewItem)) {
            return;
        }

        $this->previewItems[] = $previewItem;
    }

    /**
     * @param PreviewItem $previewItem
     */
    public function removePreviewItem(PreviewItem $previewItem)
    {
        $this->previewItems->removeElement($previewItem);
    }

}
