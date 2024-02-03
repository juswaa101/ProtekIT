<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventPlannerResource;
use App\Jobs\EventPlannerNotificationJob;
use App\Models\EventPlanner;
use Illuminate\Http\Request;

class EventPlannerController extends Controller
{

    /**
     * Display all events.
     */
    public function getEvents()
    {
        $this->authorize('view_events', EventPlanner::class);
        return EventPlannerResource::collection(EventPlanner::with(['user'])->get());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view_events', EventPlanner::class);
        return view('pages.events.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_events', EventPlanner::class);

        $request->validate([
            'title' => ['required'],
            'location' => ['required'],
            'description' => ['required'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
            'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/']
        ]);

        try {
            $event_planner = EventPlanner::create(
                [
                    'title' => $request->title,
                    'location' => $request->location,
                    'description' => $request->description,
                    'start' => $request->start,
                    'end' => $request->end,
                    'color' => $request->color,
                    'user_id' => auth()->user()->id
                ]
            );

            dispatch(new EventPlannerNotificationJob(
                $event_planner,
                'A new event - ' . $event_planner->title . ' has been created',
                'New Event'
            ));

            return response()->json(['message' => 'Event created successfully'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['message' => 'Error creating event'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventPlanner $events_planner)
    {
        $this->authorize('delete_events', EventPlanner::class);

        try {
            $events_planner->delete();
            return response()->json(['message' => 'Event deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting event'], 500);
        }
    }
}
