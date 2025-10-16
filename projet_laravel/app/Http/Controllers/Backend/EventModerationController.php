<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventModerationController extends Controller
{
    public function approve(Event $event)
    {
        $event->status = 'published';
        $event->save();

        return redirect()->back()->with('success', 'Événement approuvé.');
    }

    public function reject(Event $event)
    {
        $event->status = 'rejected';
        $event->save();

        return redirect()->back()->with('success', 'Événement rejeté.');
    }
}
