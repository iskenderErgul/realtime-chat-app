<?php

namespace App\Http\Controllers;

use App\Http\Repositories\MessageRepository;
use App\Http\Requests\Messages\ClearMessagesRequest;
use App\Http\Requests\Messages\GetMessagesRequest;
use App\Http\Requests\Messages\SendMessagesRequest;
use App\Interfaces\MessageRepositoryInterface;
use Illuminate\Http\JsonResponse;


class MessageController extends Controller
{

    protected MessageRepository $messageRepository;


    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function getMessages(GetMessagesRequest $request): JsonResponse
    {

        $messages = $this->messageRepository->getMessages($request);
        return response()->json($messages);


    }

    public function sendMessage(SendMessagesRequest $request): JsonResponse
    {
        $message = $this->messageRepository->sendMessage($request);
        return response()->json($message);


    }

    public function clearChat(ClearMessagesRequest $request): JsonResponse
    {
        $message = $this->messageRepository->clearChat($request);
        return response()->json($message);


    }

}
