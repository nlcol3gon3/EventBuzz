<?php

namespace App\Controllers;

use App\Models\OrganizerModel;

class OrganizerController extends BaseController
{
    protected $organizerModel;

    public function __construct()
    {
        $this->organizerModel = new OrganizerModel();
    }

    // Display the form to create a new organizer
    public function register()
    {
        $data = [
            'title' => 'Create Organizer - EventBuzz',
        ];

        $data['content'] = view('organizer/register', $data);
        return view('templates/base', $data);
    }

	public function login()
	{
		$data = [
			'title' => 'Organizer Login - Organizer',
		];

		$data['content'] = view('organizer/login', $data);
		return view('templates/base', $data);
	}

	public function login_action()
	{
		$organizer = $this->organizerModel->where('emailAddress', $this->request->getPost('emailAddress'))->first();

		if ($organizer && password_verify($this->request->getPost('passwordHash'), $organizer['passwordHash'])) {
			session()->set([
				'user_id' => $organizer['organizerId'],
				'email_address' => $organizer['emailAddress'],
				'user_type' => 'organizer',
			]);

			return redirect()->to('/organizer/dashboard');
		}

		return redirect()->to('/auth/organizer/login')->with('error', 'Invalid login credentials.');
	}

    // Store a new organizer
    public function store()
    {
        $data = $this->request->getPost();
		$data['passwordHash'] = password_hash($data['passwordHash'], PASSWORD_DEFAULT);

        if ($this->organizerModel->save($data)) {
            return redirect()->to('/auth/organizer/login')->with('message', 'Organizer created successfully.');
        } else {
            $errors = $this->organizerModel->errors();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }

    // Display a listing of organizers
    public function index()
    {
        $data = [
            'title' => 'Organizers - EventBuzz',
            'organizers' => $this->organizerModel->findAll(),
        ];

        $data['content'] = view('organizer/index', $data);
        return view('templates/base', $data);
    }

    // Show the form to edit an organizer
    public function edit($organizerId)
    {
        $organizer = $this->organizerModel->find($organizerId);
        if (!$organizer) {
            return redirect()->to('/organizers')->with('error', 'Organizer not found.');
        }

        $data = [
            'title' => 'Edit Organizer - EventBuzz',
            'organizer' => $organizer,
        ];

        $data['content'] = view('organizer/edit', $data);
        return view('templates/base', $data);
    }

    // Update an organizer
    public function update($organizerId)
    {
        $data = $this->request->getPost();
        if ($this->organizerModel->update($organizerId, $data)) {
            return redirect()->to('/organizers')->with('success', 'Organizer updated successfully.');
        } else {
            $errors = $this->organizerModel->errors();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }

    // Delete an organizer
    public function delete($organizerId)
    {
        if ($this->organizerModel->delete($organizerId)) {
            return redirect()->to('/organizers')->with('success', 'Organizer deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete organizer.');
        }
    }
	
	public function logout()
	{
		session()->destroy();
		return redirect()->to('/auth/organizer/login')->with('message', 'Logged out successfully.');
	}
}
?>

