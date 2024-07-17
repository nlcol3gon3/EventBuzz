<?php

namespace App\Controllers;

use App\Models\EventModel;

class Home extends BaseController
{
    public function __construct()
    {
        $this->eventModel = new EventModel();
	}

    public function index(): string
    {
		$data = [
			'title' => 'EventBuzz - Welcome',
			'content' => view('index')
		];

		return view('templates/base', $data);
    }
	
	// Display a listing of events
    public function events()
    {
        $data = [
            'title' => 'Events - EventBuzz',
            'events' => $this->eventModel->findAll(),
        ];

        $data['content'] = view('events', $data);
        return view('templates/base', $data);
    }
}
