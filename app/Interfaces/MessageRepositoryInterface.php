<?php

namespace App\Interfaces;

use App\Http\Requests\Messages\ClearMessagesRequest;
use App\Http\Requests\Messages\GetMessagesRequest;
use App\Http\Requests\Messages\SendMessagesRequest;
use Illuminate\Database\Eloquent\Collection;

interface MessageRepositoryInterface
{

    public function getMessages(GetMessagesRequest $request): Collection|array;

    public function sendMessage(SendMessagesRequest $request);

    public function clearChat(ClearMessagesRequest $request): string;

}
