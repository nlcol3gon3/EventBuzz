<?php

namespace App\Models;

use CodeIgniter\Model;

/*
* Booking Model for interacting with the Booking table
*/
class BookingModel extends Model
{
    protected $table = 'Booking';
    protected $primaryKey = 'bookingId';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'userId',
        'eventId',
        'numberOfTickets',
        'totalAmount',
        'bookingStatus',
        'createdAt',
        'updatedAt',
        'amountPaid',
        'receiptNumber',
        'paymentDate',
        'phoneNumber'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'createdAt';
    protected $updatedField = 'updatedAt';

    protected $validationRules = [
        'userId' => 'required|integer',
        'eventId' => 'required|integer',
        'numberOfTickets' => 'required|integer|greater_than[0]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /*
    * Find a booking by its ID
    * @param int $bookingId
    * @return array|null
    */
    public function findBooking($bookingId)
    {
        return $this->find($bookingId);
    }

    /*
    * Create a new booking
    * @param array $data
    * @return int|string
    */
    public function createBooking($data)
    {
        return $this->insert($data);
    }

    /*
    * Update a booking
    * @param int $bookingId
    * @param array $data
    * @return bool
    */
    public function updateBooking($bookingId, $data)
    {
        return $this->update($bookingId, $data);
    }

    /*
    * Delete a booking
    * @param int $bookingId
    * @return bool
    */
    public function deleteBooking($bookingId)
    {
        return $this->delete($bookingId);
    }

    /*
    * Get all bookings
    * @return array
    */
    public function getAllBookings()
    {
        return $this->findAll();
    }

    /*
    * Find bookings by user ID
    * @param int $userId
    * @return array
    */
    public function findBookingsByUser($userId)
    {
        return $this->where('userId', $userId)->findAll();
    }

    /*
    * Find bookings by event ID
    * @param int $eventId
    * @return array
    */
    public function findBookingsByEvent($eventId)
    {
        return $this->where('eventId', $eventId)->findAll();
    }
}
?>

