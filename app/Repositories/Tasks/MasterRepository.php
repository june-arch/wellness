<?php

namespace App\Repositories\Tasks;

use App\Models\Chats\Chat;
use App\Models\Chats\ChatItem;
use App\Models\Chats\UserChat;
use App\Models\Users\Admin;
use App\Models\Users\Member;
use App\Models\Users\Role;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = Admin::where('company_id', $request->user()->company_id)
            ->where('role_id', Role::where('name', 'Master')->first()->id)
            ->where('is_active', true)
            ->with(['thumbnail']);

        if (!$request->filters) {
            $query->orderBy('name', 'asc');
        }

        $allowedData = [
            'filters'       => ['id', 'name'],
            'globalFilters' => ['id', 'name'],
            'orders'        => ['id', 'name'],
        ];

        self::parsedRequestFilter($request, $query, $allowedData);

        return $query;
    }

    public static function chat(Admin $admin)
    {
        $user = auth()->user();

        $chat = self::getChatSession($user, $admin);

        $query = ChatItem::where('chat_id', $chat->id)
            ->with(['media'])
            ->orderBy('date', 'desc');

        $allowedData = [
            'filters'       => [],
            'globalFilters' => [],
            'orders'        => [],
        ];

        self::parsedRequestFilter(request(), $query, $allowedData);

        return $query;
    }

    public static function getChatSession(Member $member, Admin $admin)
    {
        $userChats = UserChat::select('chat_id')->where('user_id', $member->id)->orWhere('user_id', $admin->id)->groupBy('chat_id')->get();

        if ($userChats->count() == 0) {
            return self::createChatSession($member, $admin);
        }

        return $userChats->first()->chat;
    }

    protected static function createChatSession(Member $member, Admin $admin)
    {
        return DB::transaction(function () use ($member, $admin) {
            $chat = Chat::create([]);

            UserChat::insert([
                ['chat_id' => $chat->id, 'user_id' => $admin->id],
                ['chat_id' => $chat->id, 'user_id' => $member->id],
            ]);

            return $chat;
        });
    }
}
