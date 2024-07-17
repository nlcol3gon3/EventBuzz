<?php

namespace App\Models;

use CodeIgniter\Model;

/*
* Event Model for interacting with the Event table
*/
class EventModel extends Model
{
    protected $table = 'Event';
    protected $primaryKey = 'eventId';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'eventName',
        'eventDescription',
        'eventDate',
        'eventTime',
        'location',
        'maxParticipants',
        'availableTickets',
        'pricePerTicket',
        'category',
        'createdAt',
        'updatedAt',
        'organizerId'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'createdAt';
    protected $updatedField = 'updatedAt';

    protected $validationRules = [
        'eventName' => 'required|max_length[255]',
        'eventDescription' => 'max_length[1000]',
        'eventDate' => 'required|valid_date',
        'location' => 'required|max_length[255]',
        'maxParticipants' => 'required|integer',
        'pricePerTicket' => 'required|decimal',
        'category' => 'required|max_length[255]',
        'organizerId' => 'required|integer'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /*
    * Find an event by its ID
    * @param int $eventId
    * @return array|null
    */
    public function findEvent($eventId)
    {
        return $this->find($eventId);
    }

    /*
    * Create a new event
    * @param array $data
    * @return int|string
    */
    public function createEvent($data)
    {
        return $this->insert($data);
    }

    /*
    * Update an event
    * @param int $eventId
    * @param array $data
    * @return bool
    */
    public function updateEvent($eventId, $data)
    {
        return $this->update($eventId, $data);
    }

    /*
    * Delete an event
    * @param int $eventId
    * @return bool
    */
    public function deleteEvent($eventId)
    {
        return $this->delete($eventId);
    }

    /*
    * Get all events
    * @return array
    */
    public function getAllEvents()
    {
        return $this->findAll();
    }

    /*
    * Find events by organizer ID
    * @param int $organizerId
    * @return array
    */
    public function findEventsByOrganizer($organizerId)
    {
        return $this->where('organizerId', $organizerId)->findAll();
    }

    /*
    * Find events by category
    * @param string $category
    * @return array
    */
    public function findEventsByCategory($category)
    {
        return $this->where('category', $category)->findAll();
    }
}
?>

