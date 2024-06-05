<?php
namespace App\Http\Controllers\MemberApi\Masters;

use App\Http\Collections\Chats\ChatCollection;
use App\Http\Collections\Users\MasterCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\MasterResource;
use App\Models\Chats\ChatItem;
use App\Models\Users\Admin;
use App\Repositories\Tasks\MasterRepository;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterRepository::index($request);
        return $this->collection(new MasterCollection($query));
    }

    public function show(Admin $admin)
    {
        $this->guardUser($admin);

        return $this->success(new MasterResource($admin));
    }

    public function chat(Admin $admin)
    {
        $this->guardUser($admin);
        $query = MasterRepository::chat($admin);
        return $this->collection(new ChatCollection($query));
    }

    public function sendChat(Request $request, Admin $admin)
    {
        $this->guardUser($admin);

        $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:100'],
            'media'   => ['image', 'mimes:jpg,jpeg,png', 'max:2040'],
        ]);

        $user = $request->user();

        $chat = MasterRepository::getChatSession($user, $admin);

        $chatItem = ChatItem::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        $this->saveMedia($chatItem);

        return $this->success('', 'Success sending chat');
    }

    protected function guardUser(Admin $admin)
    {
        $companyId = auth()->user()->company_id;

        if ($companyId && $admin->company_id && $companyId !== $admin->company_id) {
            abort(404);
        }
    }
}
