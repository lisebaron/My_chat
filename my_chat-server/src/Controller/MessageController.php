<?php
namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\ChannelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/api", name="api_")*/
class MessageController extends ApiController
{
    /**
    * @Route("/messages", methods="GET")
    */
    public function showAll(MessageRepository $messageRepository) {
        $messages = $messageRepository->transformAll();
        return $this->respond($messages);
    }

    /**
    * @Route("/messages/{id}", methods="GET")
    */
    public function show($id, MessageRepository $messageRepository) {

        $message = $messageRepository->find($id);
        if (! $message) {
            return $this->respondNotFound();
        }
         $message = $messageRepository->transform($message);

         return $this->respond($message);
    }

    /**
     * @Route("/messages/add", methods="POST")
     */
    public function create(Request $request, MessageRepository $messageRepository, EntityManagerInterface $em) {
        $body = json_decode($request->getContent(), true);

        if ($body == null) {
            return $this->respondValidationError('Please provide a valid request!');
        }


        // validate the fields
        if (! $body['user_id']) {
            return $this->respondValidationError('Please tell me who you are!');
        }
        if (! $body['contenu']) {
            return $this->respondValidationError('Please provide a message!');
        }
        if (! $body['channel_id']) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // persist the new message
        $message = new message;
        $message->setContenu($body['contenu']);
        $message->setChannel($body['channel_id']);
        $message->setUser($body['user_id']);

        $em->persist($message);
        $em->flush();

        return $this->respondCreated($messageRepository->transform($message));

    }

    /**
     * @Route("/messages/{id}", methods="PUT")
     */
    public function edit($id, Request $request, MessageRepository $messageRepository, EntityManagerInterface $em) {
        $body = json_decode($request->getContent(), true);

        $message = $messageRepository->find($id);
        $message->setContenu($body['contenu']);

        $em->persist($message);
        $em->flush();

        return $this->respond($messageRepository->transform($message));
    }

    /**
     * @Route("/messages/{id}", methods="DELETE")
     */
    public function delete($id, MessageRepository $messageRepository, EntityManagerInterface $em) {
        $message = $messageRepository->find($id);
        $em->remove($message);
        $em->flush();
        return $this->respondWithSuccess(sprintf('Message %s successfully deleted', $message->getId()));

    }
    
}