<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Display the form to register a new user
    public function register()
    {
        $data = [
            'title' => 'Register User - EventBuzz',
        ];

        $data['content'] = view('user/register', $data);
        return view('templates/base', $data);
    }

	public function login()
	{
		$data = [
			'title' => 'User Login - User',
		];

		$data['content'] = view('user/login', $data);
		return view('templates/base', $data);
	}

	public function login_action()
	{
		$user = $this->userModel->where('emailAddress', $this->request->getPost('emailAddress'))->first();

		if ($user && password_verify($this->request->getPost('passwordHash'), $user['passwordHash'])) {
			session()->set([
				'user_id' => $user['userId'],
				'email_address' => $user['emailAddress'],
				'user_type' => 'user',
			]);
			print_r(session());

			return redirect()->to('/');
		}

		return redirect()->to('/auth/user/login')->with('error', 'Invalid login credentials.');
	}

    // Store a new user
    public function store()
    {
        $data = $this->request->getPost();
		$data['passwordHash'] = password_hash($data['passwordHash'], PASSWORD_DEFAULT);

        if ($this->userModel->save($data)) {
            return redirect()->to('/auth/user/login')->with('message', 'User created successfully.');
        } else {
            $errors = $this->userModel->errors();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }

    // Display a listing of users
    public function index()
    {
        $data = [
            'title' => 'Users - EventBuzz',
            'users' => $this->userModel->findAll(),
        ];

        $data['content'] = view('user/index', $data);
        return view('templates/base', $data);
    }

    // Show the form to edit a user
    public function edit($userId)
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit User - EventBuzz',
            'user' => $user,
        ];

        $data['content'] = view('user/edit', $data);
        return view('templates/base', $data);
    }

    // Update a user
    public function update($userId)
    {
        $data = $this->request->getPost();
        if ($this->userModel->update($userId, $data)) {
            return redirect()->to('/users')->with('success', 'User updated successfully.');
        } else {
            $errors = $this->userModel->errors();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }

    // Delete a user
    public function delete($userId)
    {
        if ($this->userModel->delete($userId)) {
            return redirect()->to('/users')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete user.');
        }
    }
	public function logout()
	{
		session()->destroy();
		return redirect()->to('/auth/user/login')->with('message', 'Logged out successfully.');
	}
}
?>

