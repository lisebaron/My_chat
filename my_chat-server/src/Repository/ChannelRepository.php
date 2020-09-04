<?php

namespace App\Repository;

use App\Entity\Channel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Channel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Channel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Channel[]    findAll()
 * @method Channel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChannelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Channel::class);
    }

    public function transform(Channel $channel) {
        return [
                'id'    =>(int) $channel->getId(),
        ];
    }

    public function findChannel($current, $receiver) {

        if ($receiver === null || $current === null)
            return 0;

        $receiverChannels = $receiver->getChannels();
        $currentChannels = $current->getChannels();

        $array1 = [];
        $i = 0;
        foreach ($receiverChannels as $channel) {
            $array1[$i] = $channel->getId();
            $i++;
        }

        $array2 = [];
        $i = 0;
        foreach ($currentChannels as $channel) {
            $array2[$i] = $channel->getId();
            $i++;
        }
        $channel = (int) array_intersect($array1, $array2);
        return $channel;
    }

    public function transformAll() {
        $channels = $this->findAll();
        $channelsArray = [];

        foreach ($channels as $channel) {
            $channelsArray[] = $this->transform($channel);
        }

        return $channelsArray;
    }

    public function transformThose(array $channels) {
        $channelsArray = [];

        foreach ($channels as $channel) {
            $channelsArray[] = $this->transform($channel);
        }

        return $channelsArray;
    }

    // /**
    //  * @return Channel[] Returns an array of Channel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Channel
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
