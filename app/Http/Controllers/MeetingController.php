<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingActionItem;
use App\Models\MeetingActionItemLog;
use App\Models\Site;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_meetings')->only(['index', 'show', 'exportPdf', 'getOpenActionItems']);
        $this->middleware('permission:create_meetings')->only(['create', 'store']);
        $this->middleware('permission:edit_meetings')->only(['edit', 'update', 'updateActionItem']);
        $this->middleware('permission:delete_meetings')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'meetings');

        // Query Meetings
        $meetingsQuery = Meeting::with(['site', 'creator'])
            ->withCount(['actionItems', 'openActionItems', 'completedActionItems']);

        if ($request->filled('meeting_search')) {
            $search = $request->meeting_search;
            $meetingsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('meeting_number', 'like', "%{$search}%")
                  ->orWhere('leader_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('meeting_type')) {
            $meetingsQuery->where('meeting_type', $request->meeting_type);
        }

        if ($request->filled('meeting_site_id')) {
            $meetingsQuery->where('site_id', $request->meeting_site_id);
        }

        if ($request->filled('meeting_date_start')) {
            $meetingsQuery->whereDate('meeting_date', '>=', $request->meeting_date_start);
        }
        if ($request->filled('meeting_date_end')) {
            $meetingsQuery->whereDate('meeting_date', '<=', $request->meeting_date_end);
        }

        $meetings = $meetingsQuery->orderBy('meeting_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10, ['*'], 'meetings_page');

        // Query Action Items (Master Tracker)
        $actionItemsQuery = MeetingActionItem::with(['meeting.site', 'pic', 'logs.user']);

        if ($request->filled('issue_search')) {
            $search = $request->issue_search;
            $actionItemsQuery->where(function ($q) use ($search) {
                $q->where('issue', 'like', "%{$search}%")
                  ->orWhere('discussion', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhereHas('pic', function ($uq) use ($search) {
                      $uq->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $actionItemsQuery->whereNotIn('status', ['Completed', 'Cancelled'])
                    ->whereNotNull('due_date')
                    ->whereDate('due_date', '<', now()->toDateString());
            } elseif ($request->status === 'active') {
                $actionItemsQuery->whereIn('status', ['Open', 'In Progress', 'Waiting Part']);
            } else {
                $actionItemsQuery->where('status', $request->status);
            }
        }

        if ($request->filled('priority')) {
            $actionItemsQuery->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $actionItemsQuery->where('category', $request->category);
        }

        if ($request->filled('pic_id')) {
            $actionItemsQuery->where('pic_id', $request->pic_id);
        }

        if ($request->filled('due_date_start')) {
            $actionItemsQuery->whereDate('due_date', '>=', $request->due_date_start);
        }
        if ($request->filled('due_date_end')) {
            $actionItemsQuery->whereDate('due_date', '<=', $request->due_date_end);
        }

        $actionItems = $actionItemsQuery->orderByRaw("CASE WHEN status IN ('Open', 'In Progress', 'Waiting Part') THEN 0 ELSE 1 END")
            ->orderBy('due_date', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'items_page');

        // Quick Stats
        $stats = [
            'total_meetings' => Meeting::count(),
            'active_issues' => MeetingActionItem::whereIn('status', ['Open', 'In Progress', 'Waiting Part'])->count(),
            'overdue_issues' => MeetingActionItem::whereNotIn('status', ['Completed', 'Cancelled'])
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', now()->toDateString())
                ->count(),
            'completed_this_month' => MeetingActionItem::where('status', 'Completed')
                ->whereMonth('completed_at', now()->month)
                ->whereYear('completed_at', now()->year)
                ->count(),
        ];

        $sites = Site::orderBy('name')->get();
        $users = User::where('status', 'active')->orderBy('nama_lengkap')->get();
        $meetingNumber = Meeting::generateMeetingNumber();

        return view('meetings.index', compact('meetings', 'actionItems', 'stats', 'sites', 'users', 'activeTab', 'meetingNumber'));
    }

    public function create()
    {
        $sites = Site::orderBy('name')->get();
        $users = User::where('status', 'active')->orderBy('nama_lengkap')->get();
        $meetingNumber = Meeting::generateMeetingNumber();
        
        return view('meetings.create', compact('sites', 'users', 'meetingNumber'));
    }

    public function getOpenActionItems(Request $request)
    {
        // Fetch open action items from previous meetings
        $query = MeetingActionItem::with(['meeting', 'pic'])
            ->whereIn('status', ['Open', 'In Progress', 'Waiting Part']);

        if ($request->filled('exclude_meeting_id')) {
            $query->where('meeting_id', '!=', $request->exclude_meeting_id);
        }

        $openItems = $query->orderBy('due_date', 'asc')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'parent_action_item_id' => $item->id,
                'meeting_number' => $item->meeting->meeting_number ?? '-',
                'meeting_title' => $item->meeting->title ?? '-',
                'meeting_date' => $item->meeting->meeting_date ? $item->meeting->meeting_date->format('d/m/Y') : '-',
                'issue' => $item->issue,
                'discussion' => $item->discussion,
                'category' => $item->category,
                'pic_id' => $item->pic_id,
                'pic_name' => $item->pic_name,
                'priority' => $item->priority,
                'due_date' => $item->due_date ? $item->due_date->format('Y-m-d') : '',
                'progress_percent' => $item->progress_percent,
                'status' => $item->status,
                'latest_update' => $item->latest_update,
            ];
        });

        return response()->json([
            'success' => true,
            'items' => $openItems,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'meeting_number' => 'required|string|unique:meetings,meeting_number',
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_type' => 'required|string',
            'items' => 'nullable|array',
            'items.*.issue' => 'required_with:items|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $meeting = Meeting::create([
                'site_id' => $request->site_id,
                'meeting_number' => $request->meeting_number,
                'title' => $request->title,
                'meeting_type' => $request->meeting_type,
                'meeting_date' => $request->meeting_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'leader_name' => $request->leader_name,
                'notetaker_name' => $request->notetaker_name,
                'attendees' => $request->attendees,
                'agenda' => $request->agenda,
                'general_notes' => $request->general_notes,
                'status' => $request->status ?: 'Published',
                'created_by' => auth()->id(),
            ]);

            if ($request->has('items') && is_array($request->items)) {
                $itemIndex = 1;
                foreach ($request->items as $itemData) {
                    if (empty($itemData['issue'])) continue;

                    $status = $itemData['status'] ?? 'Open';
                    $progress = isset($itemData['progress_percent']) ? (int)$itemData['progress_percent'] : 0;
                    $completedAt = ($status === 'Completed') ? now() : null;

                    $actionItem = MeetingActionItem::create([
                        'meeting_id' => $meeting->id,
                        'parent_action_item_id' => !empty($itemData['parent_action_item_id']) ? $itemData['parent_action_item_id'] : null,
                        'item_number' => $itemIndex++,
                        'issue' => $itemData['issue'],
                        'discussion' => $itemData['discussion'] ?? null,
                        'category' => $itemData['category'] ?? 'General',
                        'pic_id' => !empty($itemData['pic_id']) ? $itemData['pic_id'] : null,
                        'pic_name' => $itemData['pic_name'] ?? null,
                        'priority' => $itemData['priority'] ?? 'Medium',
                        'due_date' => !empty($itemData['due_date']) ? $itemData['due_date'] : null,
                        'progress_percent' => $progress,
                        'status' => $status,
                        'latest_update' => $itemData['latest_update'] ?? null,
                        'completed_at' => $completedAt,
                    ]);

                    // Initial log entry
                    MeetingActionItemLog::create([
                        'action_item_id' => $actionItem->id,
                        'user_id' => auth()->id(),
                        'progress_percent' => $progress,
                        'status' => $status,
                        'note' => !empty($itemData['latest_update']) ? $itemData['latest_update'] : 'Dicatat pada notulen ' . $meeting->meeting_number,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('meetings.show', $meeting)->with('success', 'Notulen rapat berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan notulen: ' . $e->getMessage());
        }
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['site', 'creator', 'actionItems.pic', 'actionItems.logs.user', 'actionItems.parentActionItem.meeting']);
        $users = User::where('status', 'active')->orderBy('nama_lengkap')->get();

        return view('meetings.show', compact('meeting', 'users'));
    }

    public function edit(Meeting $meeting)
    {
        $meeting->load(['actionItems.pic']);
        $sites = Site::orderBy('name')->get();
        $users = User::where('status', 'active')->orderBy('nama_lengkap')->get();

        return view('meetings.edit', compact('meeting', 'sites', 'users'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $request->validate([
            'meeting_number' => 'required|string|unique:meetings,meeting_number,' . $meeting->id,
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_type' => 'required|string',
            'items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $meeting->update([
                'site_id' => $request->site_id,
                'meeting_number' => $request->meeting_number,
                'title' => $request->title,
                'meeting_type' => $request->meeting_type,
                'meeting_date' => $request->meeting_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'leader_name' => $request->leader_name,
                'notetaker_name' => $request->notetaker_name,
                'attendees' => $request->attendees,
                'agenda' => $request->agenda,
                'general_notes' => $request->general_notes,
                'status' => $request->status ?: $meeting->status,
            ]);

            // Keep track of existing item IDs
            $existingIds = [];
            $itemIndex = 1;

            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $itemData) {
                    if (empty($itemData['issue'])) continue;

                    $status = $itemData['status'] ?? 'Open';
                    $progress = isset($itemData['progress_percent']) ? (int)$itemData['progress_percent'] : 0;
                    $completedAt = ($status === 'Completed') ? ($itemData['completed_at'] ?? now()) : null;

                    if (!empty($itemData['id'])) {
                        // Update existing
                        $actionItem = MeetingActionItem::where('meeting_id', $meeting->id)->find($itemData['id']);
                        if ($actionItem) {
                            $oldStatus = $actionItem->status;
                            $oldProgress = $actionItem->progress_percent;

                            $actionItem->update([
                                'item_number' => $itemIndex++,
                                'issue' => $itemData['issue'],
                                'discussion' => $itemData['discussion'] ?? null,
                                'category' => $itemData['category'] ?? 'General',
                                'pic_id' => !empty($itemData['pic_id']) ? $itemData['pic_id'] : null,
                                'pic_name' => $itemData['pic_name'] ?? null,
                                'priority' => $itemData['priority'] ?? 'Medium',
                                'due_date' => !empty($itemData['due_date']) ? $itemData['due_date'] : null,
                                'progress_percent' => $progress,
                                'status' => $status,
                                'latest_update' => $itemData['latest_update'] ?? $actionItem->latest_update,
                                'completed_at' => $completedAt,
                            ]);

                            $existingIds[] = $actionItem->id;

                            // If status or progress changed, log it
                            if ($oldStatus !== $status || $oldProgress !== $progress) {
                                MeetingActionItemLog::create([
                                    'action_item_id' => $actionItem->id,
                                    'user_id' => auth()->id(),
                                    'progress_percent' => $progress,
                                    'status' => $status,
                                    'note' => !empty($itemData['latest_update']) ? $itemData['latest_update'] : 'Status diperbarui menjadi ' . $status . ' (' . $progress . '%)',
                                ]);
                            }
                        }
                    } else {
                        // Create new item
                        $actionItem = MeetingActionItem::create([
                            'meeting_id' => $meeting->id,
                            'parent_action_item_id' => !empty($itemData['parent_action_item_id']) ? $itemData['parent_action_item_id'] : null,
                            'item_number' => $itemIndex++,
                            'issue' => $itemData['issue'],
                            'discussion' => $itemData['discussion'] ?? null,
                            'category' => $itemData['category'] ?? 'General',
                            'pic_id' => !empty($itemData['pic_id']) ? $itemData['pic_id'] : null,
                            'pic_name' => $itemData['pic_name'] ?? null,
                            'priority' => $itemData['priority'] ?? 'Medium',
                            'due_date' => !empty($itemData['due_date']) ? $itemData['due_date'] : null,
                            'progress_percent' => $progress,
                            'status' => $status,
                            'latest_update' => $itemData['latest_update'] ?? null,
                            'completed_at' => $completedAt,
                        ]);

                        $existingIds[] = $actionItem->id;

                        MeetingActionItemLog::create([
                            'action_item_id' => $actionItem->id,
                            'user_id' => auth()->id(),
                            'progress_percent' => $progress,
                            'status' => $status,
                            'note' => !empty($itemData['latest_update']) ? $itemData['latest_update'] : 'Item baru ditambahkan',
                        ]);
                    }
                }
            }

            // Remove deleted items
            MeetingActionItem::where('meeting_id', $meeting->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            DB::commit();

            return redirect()->route('meetings.show', $meeting)->with('success', 'Notulen rapat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui notulen: ' . $e->getMessage());
        }
    }

    public function destroy(Meeting $meeting)
    {
        try {
            $meeting->delete();
            return redirect()->route('meetings.index')->with('success', 'Notulen rapat berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus notulen: ' . $e->getMessage());
        }
    }

    public function updateActionItem(Request $request, MeetingActionItem $item)
    {
        $request->validate([
            'status' => 'required|string|in:Open,In Progress,Waiting Part,Completed,Cancelled',
            'progress_percent' => 'required|integer|min:0|max:100',
            'note' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $status = $request->status;
            $progress = (int)$request->progress_percent;
            $completedAt = ($status === 'Completed') ? now() : null;

            $item->update([
                'status' => $status,
                'progress_percent' => $progress,
                'latest_update' => $request->note,
                'completed_at' => $completedAt,
            ]);

            MeetingActionItemLog::create([
                'action_item_id' => $item->id,
                'user_id' => auth()->id(),
                'progress_percent' => $progress,
                'status' => $status,
                'note' => $request->note,
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status tindak lanjut berhasil diperbarui.',
                ]);
            }

            return back()->with('success', 'Status tindak lanjut berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Gagal mengupdate tindak lanjut: ' . $e->getMessage());
        }
    }

    public function exportPdf(Meeting $meeting)
    {
        $meeting->load(['site', 'creator', 'actionItems.pic', 'actionItems.parentActionItem.meeting']);
        
        return view('meetings.pdf', compact('meeting'));
    }
}
