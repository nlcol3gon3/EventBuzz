<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Pages extends Controller {

	public function view($page = 'homepage') {
		if (!is_file(APPPATH.'/Views/main/'.$page.'.php')) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
		}

		$data['title'] = ucfirst(str_replace('_', ' ', $page)); // Capitalize the first letter and replace underscores with spaces
		$data['content'] = view('main/'.$page);

		return view('templates/base', $data);
	}
}
