<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        // Require specific permissions for chat
        $this->middleware('permission:view_chat')->only(['index', 'getUsers', 'getMessages', 'unreadCount', 'searchDocument']);
        $this->middleware('permission:send_chat')->only(['send']);
        // 'clearChat' now doesn't require a special permission because it's limited to the user's own chat history
    }

    public function index()
    {
        $authId = Auth::id();
        $users = User::where('id', '!=', $authId)
            ->where('status', 'active')
            ->with('site:id,name,code')
            ->select('id', 'nama_lengkap', 'email', 'avatar', 'site_id')
            ->get()
            ->map(function ($user) use ($authId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $authId)
                    ->whereNull('read_at')
                    ->count();
                $user->avatar_url = $user->avatar_url;
                $user->site_name = $user->site ? ($user->site->name . ($user->site->code ? ' (' . $user->site->code . ')' : '')) : 'Head Office / Superadmin';
                $user->site_code = $user->site ? ($user->site->code ?? $user->site->name) : 'HO';
                return $user;
            });
            
        // Kelompokkan per Site, dengan unread_count terbanyak diutamakan
        $groupedUsers = $users->groupBy('site_name');
        $users = $users->sortByDesc('unread_count')->values();
        
        return view('chat.index', compact('users', 'groupedUsers'));
    }

    public function getUsers()
    {
        $authId = Auth::id();
        $users = User::where('id', '!=', $authId)
            ->where('status', 'active')
            ->with('site:id,name,code')
            ->select('id', 'nama_lengkap', 'email', 'avatar', 'site_id')
            ->get()
            ->map(function ($user) use ($authId) {
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $authId)
                    ->whereNull('read_at')
                    ->count();
                $user->avatar_url = $user->avatar_url;
                $user->site_name = $user->site ? ($user->site->name . ($user->site->code ? ' (' . $user->site->code . ')' : '')) : 'Head Office / Superadmin';
                $user->site_code = $user->site ? ($user->site->code ?? $user->site->name) : 'HO';
                return $user;
            })->sortByDesc('unread_count')->values();

        return response()->json($users);
    }

    public function getMessages($userId)
    {
        $authId = Auth::id();
        $messages = Message::where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->with('sender:id,nama_lengkap,avatar,site_id', 'sender.site:id,name,code')
            ->get()
            ->map(function ($msg) {
                if ($msg->sender) {
                    $msg->sender->avatar_url = $msg->sender->avatar_url;
                }
                return $msg;
            });

        // Tandai pesan yang diterima sebagai sudah dibaca
        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body'        => 'nullable|string|max:5000',
            'file'        => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,webp,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,csv',
            'attachment'  => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,webp,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,csv',
        ]);

        if (empty($request->body) && !$request->hasFile('file') && !$request->hasFile('attachment')) {
            return response()->json(['message' => 'Pesan atau lampiran berkas wajib diisi.'], 422);
        }

        $uploadedFile = $request->file('file') ?? $request->file('attachment');
        $attachmentPath = null;
        $attachmentName = null;
        $attachmentType = null;
        $attachmentSize = null;

        if ($uploadedFile) {
            $attachmentName = $uploadedFile->getClientOriginalName();
            $attachmentSize = $uploadedFile->getSize();
            $mime = $uploadedFile->getMimeType();
            $ext = strtolower($uploadedFile->getClientOriginalExtension());

            if (str_starts_with($mime, 'image/') || in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $attachmentType = 'image';
            } else {
                $attachmentType = 'document';
            }

            $attachmentPath = $uploadedFile->store('chat_attachments', 'public');
        }

        $message = Message::create([
            'sender_id'       => Auth::id(),
            'receiver_id'     => $request->receiver_id,
            'body'            => $request->body ?? '',
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_type' => $attachmentType,
            'attachment_size' => $attachmentSize,
        ]);

        $message->load('sender:id,nama_lengkap,avatar,site_id', 'sender.site:id,name,code');
        if ($message->sender) {
            $message->sender->avatar_url = $message->sender->avatar_url;
        }

        // Broadcast via Reverb if available
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            // broadcast fallback silently
        }

        return response()->json($message);
    }

    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())->whereNull('read_at')->count();
        return response()->json(['count' => $count]);
    }
    public function searchDocument(Request $request)
    {
        $query = $request->get('q', '');
        $results = [];

        if (strlen($query) >= 2) {
            $wos = \App\Models\WorkOrder::where('no_wo', 'like', "%{$query}%")
                ->orWhereHas('unit', function ($q) use ($query) {
                    $q->where('nomor_unit', 'like', "%{$query}%");
                })
                ->limit(5)->get();

            foreach ($wos as $wo) {
                $results[] = [
                    'id' => 'wo_' . $wo->id,
                    'type' => 'Work Order',
                    'title' => $wo->no_wo,
                    'desc' => 'Unit: ' . ($wo->unit->nomor_unit ?? '-'),
                    'url' => route('work-orders.show', $wo),
                ];
            }

            $jwos = \App\Models\Jwo::where('no_jwo', 'like', "%{$query}%")
                ->orWhere('problem_description', 'like', "%{$query}%")
                ->limit(5)->get();
                
            foreach ($jwos as $jwo) {
                $results[] = [
                    'id' => 'jwo_' . $jwo->id,
                    'type' => 'JWO',
                    'title' => $jwo->no_jwo,
                    'desc' => \Illuminate\Support\Str::limit($jwo->problem_description, 30),
                    'url' => route('jwos.show', $jwo->id),
                ];
            }
        }

        return response()->json($results);
    }

    public function clearChat($userId)
    {
        $authId = Auth::id();
        Message::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $authId);
        })->delete();

        return response()->json(['status' => 'success', 'message' => 'Riwayat percakapan berhasil dibersihkan.']);
    }
}
