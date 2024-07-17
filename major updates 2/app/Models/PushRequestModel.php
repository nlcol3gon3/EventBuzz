<?php

namespace App\Models;

use CodeIgniter\Model;

class PushRequestModel extends Model
{
    protected $table = 'PushRequests';
    protected $primaryKey = 'pushRequestId';
    protected $allowedFields = ['checkoutRequestId', 'orderId'];

    public function createPushRequest($details)
    {
        $this->insert($details);
        return $this->insertID();
    }

    public function removePushRequest($id)
    {
        return $this->delete($id);
    }

    public function findPushRequestByCheckoutRequestId($checkoutRequestId)
    {
        return $this->where('checkoutRequestId', $checkoutRequestId)->first();
    }
}
?>

