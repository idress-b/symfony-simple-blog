<?php
namespace App\EventSubscriber;

use App\Entity\Post;
use App\Service\FileUploaderNoSlug;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
      
    public static function getSubscribedEvents()
    {
        return [
           BeforeEntityDeletedEvent::class => ['removeImageFromBucket'],
        ];
    }

    public function removeImageFromBucket(BeforeEntityDeletedEvent $event )
    {
        $entity = $event->getEntityInstance();
        
        if (!($entity instanceof Post)) {
           
            return;
        }
       
         $imageFile = $entity->getImage();
         if ($imageFile !== null){
            
            $fileUploader = new FileUploaderNoSlug;
             $fileUploader->deleteFile($imageFile);
         }
       
    }
}