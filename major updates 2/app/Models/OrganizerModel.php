<?php

namespace App\Models;

use CodeIgniter\Model;

/*
* Organizer Model for interacting with the Organizer table
*/
class OrganizerModel extends Model
{
    protected $table = 'Organizer';
    protected $primaryKey = 'organizerId';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'organizerName',
        'passwordHash',
        'emailAddress',
        'phoneNumber',
        'createdAt',
        'updatedAt'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'createdAt';
    protected $updatedField = 'updatedAt';

    protected $validationRules = [
        'organizerName' => 'required|max_length[255]',
        'emailAddress' => 'required|valid_email|max_length[255]',
        'phoneNumber' => 'max_length[20]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /*
    * Find an organizer by its ID
    * @param int $organizerId
    * @return array|null
    */
    public function findOrganizer($organizerId)
    {
        return $this->find($organizerId);
    }

    /*
    * Create a new organizer
    * @param array $data
    * @return int|string
    */
    public function createOrganizer($data)
    {
        return $this->insert($data);
    }

    /*
    * Update an organizer
    * @param int $organizerId
    * @param array $data
    * @return bool
    */
    public function updateOrganizer($organizerId, $data)
    {
        return $this->update($organizerId, $data);
    }

    /*
    * Delete an organizer
    * @param int $organizerId
    * @return bool
    */
    public function deleteOrganizer($organizerId)
    {
        return $this->delete($organizerId);
    }

    /*
    * Get all organizers
    * @return array
    */
    public function getAllOrganizers()
    {
        return $this->findAll();
    }
}
?>

