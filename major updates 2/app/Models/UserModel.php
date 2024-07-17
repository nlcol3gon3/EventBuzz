<?php

namespace App\Models;

use CodeIgniter\Model;

/*
* User Model for interacting with the User table
*/
class UserModel extends Model
{
    protected $table = 'User';
    protected $primaryKey = 'userId';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'fullName',
        'emailAddress',
        'phoneNumber',
        'passwordHash',
        'createdAt',
        'updatedAt'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'createdAt';
    protected $updatedField = 'updatedAt';

    protected $validationRules = [
        'fullName' => 'max_length[255]',
        'emailAddress' => 'required|valid_email|max_length[255]',
        'phoneNumber' => 'max_length[20]',
        'passwordHash' => 'required'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /*
    * Find a user by its ID
    * @param int $userId
    * @return array|null
    */
    public function findUser($userId)
    {
        return $this->find($userId);
    }

    /*
    * Find a user by email address
    * @param string $email
    * @return array|null
    */
    public function findByEmail($email)
    {
        return $this->where('emailAddress', $email)->first();
    }

    /*
    * Create a new user
    * @param array $data
    * @return int|string
    */
    public function createUser($data)
    {
        return $this->insert($data);
    }

    /*
    * Update a user
    * @param int $userId
    * @param array $data
    * @return bool
    */
    public function updateUser($userId, $data)
    {
        return $this->update($userId, $data);
    }

    /*
    * Delete a user
    * @param int $userId
    * @return bool
    */
    public function deleteUser($userId)
    {
        return $this->delete($userId);
    }

    /*
    * Get all users
    * @return array
    */
    public function getAllUsers()
    {
        return $this->findAll();
    }
}
?>

