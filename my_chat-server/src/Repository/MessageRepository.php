<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function transform(Message $message) {
        return [
            'id'    =>(int) $message->getId(),
            'contenu'    =>(string) $message->getContenu(),
            'channel'    =>(int) $message->getChannel(),
            'user'    =>(int) $message->getUser()
        ];
    }

    public function transformWithUsername(Message $message, UserRepository $userRepository) {
        $sender = $userRepository->findUsername($message->getUser());
        return [
            'id'    =>(int) $message->getId(),
            'contenu'    =>(string) $message->getContenu(),
            'channel'    =>(int) $message->getChannel(),
            'user'    =>(string) $sender
        ];
    }

    public function transformAll() {
        $messages = $this->findAll();
        $messagesArray = [];

        foreach ($messages as $message) {
            $messagesArray[] = $this->transform($message);
        }

        return $messagesArray;
    }

    public function transformThose(array $messages) {
        $messagesArray = [];

        foreach ($messages as $message) {
            $messagesArray[] = $this->transform($message);
        }

        return $messagesArray;
    }

    public function transformThoseWithUsername(array $messages, UserRepository $userRepository) {
        $messagesArray = [];

        foreach ($messages as $message) {
            $messagesArray[] = $this->transformWithUsername($message, $userRepository);
        }

        return $messagesArray;
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
