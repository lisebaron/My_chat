<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Repository\ChannelRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api", name="api_")*/
class ChannelController extends ApiController
{

    /**
     * @Route("/channels", methods="GET")
     */
    public function showAll(ChannelRepository $channelRepository) {
        $channels = $channelRepository->transformAll();
        return $this->respond($channels);
    }

    /**
     * @Route("/channels/create", methods="POST")
     */
    public function create(Request $request, UserRepository $userRepository, ChannelRepository $channelRepository, EntityManagerInterface $em) {
        $body = json_decode($request->getContent(), true);

        $current = $userRepository->findOneBy(
            ['username' => $body['current']]
        );
        $receiver = $userRepository->findOneBy(
            ['username' => $body['receiver']]
        );

        if ($receiver === null || $current === null)
            return $this->respondValidationError("The user you provided doesn't exist.");

        $channel = new channel;
        $channel->addUser($current);
        $channel->addUser($receiver);

        $em->persist($channel);
        $em->flush();

        return $this->respondCreated($channelRepository->transform($channel));
    }

    /**
     * @Route("/channels/users", methods="POST")
     */
    public function getUsers(Request $request, ChannelRepository $channelRepository, UserRepository $userRepository) {
        $body = json_decode($request->getContent(), true);

        $current = $userRepository->findOneBy(
            ['username' => $body['current']]
        );

        if ($current === null)
            return $this->respondValidationError("The user you provided doesn't exist.");

        $channels = $current->getChannels();
        $users = [];
        $i = 0;

        foreach ($channels as $channel) {
            $tmp = $channel->getUsers();
            foreach ($tmp as $user) {
                if ($user->getUsername() != $current->getUsername()) {
//                    $users[$i] = $user;
//                    $users[$i] = $userRepository->tranformuser($user);
                    $users[$i] = [
                        'channel_id' => $channelRepository->findChannel($current, $user),
                        'username' => $user->getUsername()
                    ];
                    $i++;
                }
            }
        }

//        $users = $userRepository->transformThoseWithoutPwd($users);
        return $this->respond($users);
    }


//    /**
//     * @Route("/channels/messages/{id}", methods="POST")
//     */
//    public function getPrivateMessages($id, Request $request, UserRepository $userRepository, ChannelRepository $channelRepository, MessageRepository $messageRepository) {
//        $body = json_decode($request->getContent(), true);
//
//        $receiver = $userRepository->find($id);
//        $current = $userRepository->find($body['current_id']);
//
//        $channel = $channelRepository->findChannel($current, $receiver);
//
//        $messages = $messageRepository->findBy(
//            ['channel' => (int) $channel]
//        );
//
//        $messages = $messageRepository->transformThose($messages);
//        return $this->respond($messages);
//    }

    /**
     * @Route("/channels/{id}", methods="GET")
     */
    public function showMessages($id, MessageRepository $messageRepository, UserRepository $userRepository) {
        $messages = $messageRepository->findBy(
            ['channel' => (int) $id]
        );

        $messages = $messageRepository->transformThoseWithUsername($messages, $userRepository);
        return $this->respond($messages);
    }

    /**
     * @Route("/channels/{id}", methods="DELETE")
     */
    public function delete($id, ChannelRepository $channelRepository, EntityManagerInterface $em) {
        $channel = $channelRepository->find($id);
        $em->remove($channel);
        $em->flush();
        return $this->respondWithSuccess(sprintf('Channel successfully deleted'));
    }
}
