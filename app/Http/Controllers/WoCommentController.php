<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WoComment;
use Illuminate\Http\Request;
use App\Notifications\WoDiscussionNotification;

class WoCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_wo_comments')->only(['index']);
        $this->middleware('permission:create_wo_comments')->only(['store']);
        $this->middleware('permission:delete_wo_comments')->only(['destroy']);
    }

    public function index(WorkOrder $workOrder)
    {
        if (!auth()->user()->hasRole('Super Admin') && auth()->user()->site_id !== $workOrder->site_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comments = $workOrder->comments()
            ->whereNull('parent_id')
            ->with(['user:id,nama_lengkap,email,avatar', 'replies.user:id,nama_lengkap,email,avatar'])
            ->oldest()
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'body' => $comment->body,
                    'created_at' => $comment->created_at->toISOString(),
                    'user' => [
                        'nama_lengkap' => $comment->user->nama_lengkap ?? 'Unknown',
                        'avatar_url' => $comment->user->avatar_url,
                        'email' => $comment->user->email,
                    ],
                    'attachment_url' => $comment->attachment_url,
                    'attachment_name' => $comment->attachment_name,
                    'attachment_type' => $comment->attachment_type,
                    'replies' => $comment->replies->map(function($reply) {
                        return [
                            'id' => $reply->id,
                            'parent_id' => $reply->parent_id,
                            'user_id' => $reply->user_id,
                            'body' => $reply->body,
                            'created_at' => $reply->created_at->toISOString(),
                            'user' => [
                                'nama_lengkap' => $reply->user->nama_lengkap ?? 'Unknown',
                                'avatar_url' => $reply->user->avatar_url,
                            ],
                            'attachment_url' => $reply->attachment_url,
                            'attachment_name' => $reply->attachment_name,
                            'attachment_type' => $reply->attachment_type,
                        ];
                    })
                ];
            });

        return response()->json($comments);
    }

    public function store(Request $request, WorkOrder $workOrder)
    {
        if (!auth()->user()->hasRole('Super Admin') && auth()->user()->site_id !== $workOrder->site_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'body' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:wo_comments,id',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf,doc,docx,xls,xlsx,csv,txt,zip|max:10240' // 10MB max overall
        ]);

        $attachmentPath = null;
        $attachmentName = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            
            // Check if it's an image
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'image/')) {
                $request->validate(['attachment' => 'max:2048']); // Max 2MB for images
                $attachmentType = 'image';
            } else {
                $attachmentType = 'document';
            }

            $attachmentPath = $file->store('wo_discussions', 'public');
        }

        $comment = $workOrder->comments()->create([
            'parent_id' => $request->parent_id,
            'user_id' => auth()->id(),
            'body' => $request->body,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_type' => $attachmentType,
        ]);

        $comment->load('user:id,nama_lengkap,email,avatar');
        
        // Notifications
        if ($request->parent_id) {
            $parent = WoComment::find($request->parent_id);
            if ($parent && $parent->user_id !== auth()->id()) {
                $parent->user->notify(new WoDiscussionNotification($comment, 'comment'));
            }
        } else {
            // Notify WO creator if it's a new post (and not by the creator)
            if ($workOrder->creator && $workOrder->created_by !== auth()->id()) {
                $workOrder->creator->notify(new WoDiscussionNotification($comment, 'post'));
            }
        }

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'parent_id' => $comment->parent_id,
                'user_id' => $comment->user_id,
                'body' => $comment->body,
                'created_at' => $comment->created_at->toISOString(),
                'user' => [
                    'nama_lengkap' => $comment->user->nama_lengkap ?? 'Unknown',
                    'avatar_url' => $comment->user->avatar_url,
                    'email' => $comment->user->email,
                ],
                'attachment_url' => $comment->attachment_url,
                'attachment_name' => $comment->attachment_name,
                'attachment_type' => $comment->attachment_type,
                'replies' => []
            ]
        ]);
    }

    public function destroy(WorkOrder $workOrder, WoComment $comment)
    {
        // Only author or super admin can delete
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('Super Admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($comment->attachment_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($comment->attachment_path);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}
