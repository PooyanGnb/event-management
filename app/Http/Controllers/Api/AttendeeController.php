<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use CanLoadRelationships;

    private array $relations = ['user'];


    public function index(Event $event)
    {
        Gate::allows('viewAny', Attendee::class);
        $attendees = $this->loadRelationships($event->attendees()->latest());

        return AttendeeResource::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        Gate::authorize('create', Attendee::class);

        $attendee = $this->loadRelationships($event->attendees()->create([
            'user_id' => $request->user()->id,
        ]));

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        Gate::allows('view', $attendee);
        return new AttendeeResource($this->loadRelationships($attendee));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */

    // $event is string and not a class beacuse we dont need to fetch anything from the event 
    public function destroy(Event $event, Attendee $attendee)
    {

        Gate::authorize('delete', $attendee);
        $attendee->delete();

        return response(status:204);
    }
}
