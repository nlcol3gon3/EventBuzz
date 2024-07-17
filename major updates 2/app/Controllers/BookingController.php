<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\EventModel;
use App\Models\UserModel;
use App\Models\PushRequestModel;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class BookingController extends BaseController
{
	protected $bookingModel;
	protected $eventModel;
	protected $userModel;
	protected $pushRequestModel;
	protected $httpClient;

	public function __construct()
	{
		$this->bookingModel = new BookingModel();
		$this->eventModel = new EventModel();
		$this->userModel = new UserModel();
		$this->pushRequestModel = new PushRequestModel();
		$this->httpClient = Services::curlrequest();
	}
	// Override the response method to include CORS headers
	private function withCors(ResponseInterface $response)
	{
		return $response->setHeader('Access-Control-Allow-Origin', '*')
				  ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
				  ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
	}

	// Display a listing of bookings
	public function index()
	{
		$data = [
			'title' => 'Bookings - EventBuzz',
			'bookings' => $this->bookingModel->findAll(),
		];

		$data['content'] = view('booking/index', $data);
		return view('templates/base', $data);
	}

	// View a single booking
	public function view($bookingId)
	{
		$booking = $this->bookingModel->find($bookingId);
		if (!$booking) {
			return redirect()->to('/bookings')->with('error', 'Booking not found.');
		}

		$data = [
			'title' => 'Booking Details - EventBuzz',
			'booking' => $booking,
			'event' => $this->eventModel->find($booking['eventId']),
			'user' => $this->userModel->find($booking['userId']),
		];

		$data['content'] = view('booking/view', $data);
		return view('templates/base', $data);
	}

	// Display the form to create a new booking
	public function create($eventId)
	{
		$event = $this->eventModel->find($eventId);
		if (!$event) {
			return redirect()->to('/events')->with('error', 'Event not found.');
		}

		$data = [
			'title' => 'Create Booking - EventBuzz',
			'event' => $event,
		];

		$data['content'] = view('user/create_booking', $data);
		return view('templates/base', $data);
	}

	// Store a new booking
	public function store()
	{
		$data = $this->request->getPost();
		$data['userId'] = session()->get('user_id');

		if ($this->bookingModel->save($data)) {
			$bookingId = $this->bookingModel->insertID();
			return redirect()->to('/user/bookings/payment/' . $bookingId)->with('success', 'Booking created successfully. Proceed to payment.');
		} else {
			$errors = $this->bookingModel->errors();
			return redirect()->back()->with('error', implode(', ', $errors));
		}
	}

	// Display payment details form
	public function payment($bookingId)
	{
		$booking = $this->bookingModel->find($bookingId);
		if (!$booking) {
			return redirect()->to('/bookings')->with('error', 'Booking not found.');
		}

		$data = [
			'title' => 'Payment Details - EventBuzz',
			'booking' => $booking,
			'event' => $this->eventModel->find($booking['eventId']),
		];

		$data['content'] = view('user/payment', $data);
		return view('templates/base', $data);
	}

	// Endpoint to initiate STK push
	public function stkPush()
	{
		$data = $this->request->getJSON(true);

		$phoneNumber = $data['phoneNumber'];
		$amount = $data['amount'];
		$orderId = $data['orderId'];

		if (!$phoneNumber || !$amount || !$orderId) {
			return $this->response->setJSON(['error' => 'Missing required fields'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
		}

		// Get access token
		$accessToken = $this->getAccessToken();
		if (!$accessToken) {
			return $this->response->setJSON(['error' => 'Unable to get access token'])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
		}

		// Initiate STK Push
		$response = $this->initiateStkPush($accessToken, $amount, $phoneNumber);
		if (isset($response['errorMessage'])) {
			return $this->response->setJSON(['error' => $response['errorMessage']])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
		}

		// Save the PushRequest
		$this->pushRequestModel->save([
			'checkoutRequestId' => $response['CheckoutRequestID'],
			'orderId' => $orderId,
		]);

		return $this->response->setJSON([
			'code' => $response['ResponseCode'],
			'message' => $response['CustomerMessage'],
			'checkoutRequestId' => $response['CheckoutRequestID'],
		])->setStatusCode(ResponseInterface::HTTP_OK);
	}

	// Endpoint to query STK status
	public function stkQuery()
	{
		$data = $this->request->getJSON(true);
		$checkoutRequestId = 'ws_CO_13072024230307943114742348';

		if (!$checkoutRequestId) {
			return $this->response->setJSON(['error' => 'Missing checkoutRequestId'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
		}

		// Get access token
		$accessToken = $this->getAccessToken();
		if (!$accessToken) {
			return $this->response->setJSON(['error' => 'Unable to get access token'])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
		}

		// Query STK Status
		$response = $this->queryStkStatus($accessToken, $checkoutRequestId);
		if (isset($response['errorMessage'])) {
			return $this->response->setJSON(['error' => $response['errorMessage']])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
		}

		return $this->response->setJSON([
			'code' => $response['ResultCode'],
			'message' => $response['ResultDesc'],
		])->setStatusCode(ResponseInterface::HTTP_OK);
	}

	private function getAccessToken()
	{
		$credentials = base64_encode('E7RkuNKKVFG3p2nWjEM78RcbFOwH2qb5UHpGvpOhzodFGbHV:tQw44mUODFBqUk25oS5NweJBMrlvdWwkYdap6P3895kekW2LmLFcHT4Lvjr4figm');
		$response = $this->httpClient->request('GET', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials', [
			'headers' => [
				'Authorization' => 'Basic ' . $credentials,
				'Content-Type' => 'application/json'
			]
		]);

		if ($response->getStatusCode() !== 200) {
			return null;
		}

		$result = json_decode($response->getBody(), true);
		return $result['access_token'];
	}

	private function initiateStkPush($accessToken, $amount, $phoneNumber)
	{
		$timestamp = date('YmdHis');
		$password = base64_encode('174379' . 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919' . $timestamp);

		$payload = [
			'BusinessShortCode' => '174379',
			'Password' => $password,
			'Timestamp' => $timestamp,
			'TransactionType' => 'CustomerPayBillOnline',
			'Amount' => $amount,
			'PartyA' => $phoneNumber,
			'PartyB' => '174379',
			'PhoneNumber' => $phoneNumber,
			'CallBackURL' => 'https://example.com/payment/stk-callback',
			'AccountReference' => 'EventBooking',
			'TransactionDesc' => 'Payment for event booking'
		];

		$response = $this->httpClient->request('POST', 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
			'headers' => [
				'Authorization' => 'Bearer ' . $accessToken,
				'Content-Type' => 'application/json'
			],
			'json' => $payload
		]);

		return json_decode($response->getBody(), true);
	}

	// Payment callback endpoint
	public function paymentCallback()
	{
		$response = $this->request->getJSON(true);
		$response = $response['Body']['stkCallback'];

		$resultCode = $response['ResultCode'];
		if ($resultCode == 0) {
			$transactionDetails = $response['CallbackMetadata']['Item'];

			$checkoutRequestId = $response['CheckoutRequestID'];
			$pushRequest = $this->pushRequestModel->findPushRequestByCheckoutRequestId($checkoutRequestId);
			if (!$pushRequest) {
				return $this->response->setJSON(['error' => 'Invalid request'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
			}

			$amount = $this->getValueFromCallbackMetadata($transactionDetails, 'Amount');
			$details = [
				'merchantRequestId' => $response['MerchantRequestID'],
				'amount' => $amount,
				'mpesaReceiptNumber' => $this->getValueFromCallbackMetadata($transactionDetails, 'MpesaReceiptNumber'),
				'transactionDate' => $this->getValueFromCallbackMetadata($transactionDetails, 'TransactionDate'),
				'phoneNumber' => $this->getValueFromCallbackMetadata($transactionDetails, 'PhoneNumber')
			];

			$this->completeOrder($pushRequest['orderId'], $details);

			// Remove PushRequest record
			$this->pushRequestModel->removePushRequest($pushRequest['pushRequestId']);
		}

		return $this->response->setJSON(['message' => 'Payment details received'])->setStatusCode(ResponseInterface::HTTP_OK);
	}

	private function getValueFromCallbackMetadata($metadata, $name)
	{
		foreach ($metadata as $item) {
			if ($item['Name'] === $name) {
				return $item['Value'];
			}
		}
		return null;
	}

	private function completeOrder($orderId, $details)
	{
		$order = $this->bookingModel->find($orderId);
		// Implement the logic to complete the order and mark it as paid
		// For example:
		// $order['status'] = 'Paid';
		// $this->bookingModel->save($order);
	}

	private function queryStkStatus($accessToken, $checkoutRequestId)
	{
		$timestamp = date('YmdHis');
		$password = base64_encode('174379' . 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919' . $timestamp);

		$payload = [
			'BusinessShortCode' => '174379',
			'Password' => $password,
			'Timestamp' => $timestamp,
			'CheckoutRequestID' => $checkoutRequestId
		];

		$response = $this->httpClient->request('POST', 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query', [
			'headers' => [
				'Authorization' => 'Bearer ' . $accessToken,
				'Content-Type' => 'application/json'
			],
			'json' => $payload
		]);
		return json_decode($response->getBody(), true);
	}
}
?>

