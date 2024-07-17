<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\OrganizerModel;

class EventController extends BaseController
{
    protected $eventModel;
    protected $organizerModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

    // Display the form to create a new event
    public function create()
    {
        $data = [
            'title' => 'Create Event - EventBuzz',
        ];

        $data['content'] = view('organizer/create_event', $data);
        return view('templates/base', $data);
    }

    // Store a new event
    public function store()
    {
        $data = $this->request->getPost();
		$data['organizerId'] = session()->get('user_id');
        if ($this->eventModel->save($data)) {
            return redirect()->to('/organizer/dashboard')->with('message', 'Event created successfully.');
        } else {
            $errors = $this->eventModel->errors();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }

    // Display a listing of events
    public function index()
    {
        $data = [
            'title' => 'Events - EventBuzz',
            'events' => $this->eventModel->findAll(),
        ];

        $data['content'] = view('organizer/dashboard', $data);
        return view('templates/base', $data);
    }

    // Show the form to edit an event
    public function edit($eventId)
    {
        $event = $this->eventModel->find($eventId);
        if (!$event) {
            return redirect()->to('/events')->with('error', 'Event not found.');
        }

        $data = [
            'title' => 'Edit Event - EventBuzz',
            'event' => $event,
        ];

        $data['content'] = view('organizer/edit_event', $data);
        return view('templates/base', $data);
    }

    // Update an event
    public function update($eventId)
    {
        $data = $this->request->getPost();
        if ($this->eventModel->update($eventId, $data)) {
            return redirect()->to('/organizer/dashboard')->with('message', 'Event updated successfully.');
        } else {
            $errors = $this->eventModel->errors();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }

    // Delete an event
    public function delete($eventId)
    {
        if ($this->eventModel->delete($eventId)) {
            return redirect()->to('/events')->with('message', 'Event deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete event.');
        }
    }
}
?>

