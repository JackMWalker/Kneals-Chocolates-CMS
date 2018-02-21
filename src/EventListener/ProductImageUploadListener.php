<?php
/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 06/02/2018
 * Time: 23:03
 */

namespace App\EventListener;

use App\Entity\ProductImage;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ProductImageUploadListener
{
    private $uploader;
    private $directory;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
        $this->directory = 'products';
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof ProductImage) {
            return;
        }

        $file = $entity->getImage();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file, $this->directory);
            $entity->setImage($fileName);
        } elseif($file instanceof File) {
            $entity->setImage($entity->getImageName());
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof ProductImage) {
            return;
        }

        if ($fileName = $entity->getImage()) {
            $entity->setImageName($fileName);
            $entity->setImage(new File($this->uploader->getTargetDir().'/'.$this->directory.'/'.$fileName));
        }
    }
}