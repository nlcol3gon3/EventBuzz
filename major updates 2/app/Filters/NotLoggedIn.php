<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class NotLoggedIn implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
	{
		if (session()->has('user_type')) {
			$userType = session()->get('user_type');
            return redirect()->to($userType .'/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No implementation needed for after filter
    }
}

