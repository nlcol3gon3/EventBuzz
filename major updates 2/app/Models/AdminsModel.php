<?php

namespace App\Models;

use CodeIgniter\Model;

/*
* Admins Model for interacting with the Admins table
*/
class AdminsModel extends Model
{
    protected $table = 'Admins';
    protected $primaryKey = 'adminId';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'userName',
        'passwordHash',
        'emailAddress',
        'createdAt',
        'updatedAt'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'createdAt';
    protected $updatedField = 'updatedAt';

    protected $validationRules = [
        'userName' => 'required|max_length[255]',
        'passwordHash' => 'required',
        'emailAddress' => 'required|valid_email|max_length[255]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /*
    * Find an admin by its ID
    * @param int $adminId
    * @return array|null
    */
    public function findAdmin($adminId)
    {
        return $this->find($adminId);
    }

    /*
    * Create a new admin
    * @param array $data
    * @return int|string
    */
    public function createAdmin($data)
    {
        return $this->insert($data);
    }

    /*
    * Update an admin
    * @param int $adminId
    * @param array $data
    * @return bool
    */
    public function updateAdmin($adminId, $data)
    {
        return $this->update($adminId, $data);
    }

    /*
    * Delete an admin
    * @param int $adminId
    * @return bool
    */
    public function deleteAdmin($adminId)
    {
        return $this->delete($adminId);
    }

    /*
    * Get all admins
    * @return array
    */
    public function getAllAdmins()
    {
        return $this->findAll();
    }
}
?>

