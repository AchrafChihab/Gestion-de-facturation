<?php
namespace App\EventListener;
 
use App\Entity\Commande;
use Doctrine\ORM\Events;  
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\Session\Session;  
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
 

class CommandeCodeGenerator
{


    private $session;  

    public function __construct(Session $session)
    {
        $this->session       = $session;  
    } 

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Commande) {
            return;
        }
        if($entity != null){

            $now     = new \DateTime();
            $year    = $now->format('Y');
            $prefix  = '#';

            $em = $args->getEntityManager();  
            $maxCode = $em->getRepository(Commande::class)->findMaxCode($year, $prefix);
        
            if ($maxCode[1]) {
                $increment = substr($maxCode[1], -4);
                $increment = (int)$increment + 1;
            } else {
                $increment = 1;
            }  

            $code = $prefix . $year . sprintf('%04d', $increment);  
            $args->getObject()->setNom($code);
        }
 

    }

 
 
}