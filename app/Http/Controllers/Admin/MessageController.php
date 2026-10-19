<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $messages = ContactMessage::query()
            ->orderByRaw('CASE WHEN is_read = 0 THEN 0 ELSE 1 END')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.messages.index', compact('messages'));
    }

    public function markRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => true]);

        return back()->with('status', 'Message from "'.$message->name.'" marked as read.');
    }

    public function markAllRead(): RedirectResponse
    {
        ContactMessage::unread()->update(['is_read' => true]);

        return back()->with('status', 'All messages marked as read.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('status', 'Message deleted.');
    }
}
