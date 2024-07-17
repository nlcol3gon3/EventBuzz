<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UserAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
	{
		$userType = $arguments[0] ?? null;
		
		if ($userType && (session()->get('user_type') !== $userType)) {
            return redirect()->to('/auth/user/login')->with('error', 'You must be logged in');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No implementation needed for after filter
    }
}

