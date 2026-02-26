<?php

namespace App\Models;

use CodeIgniter\Model;

class DGSnmpModel extends Model
{
    protected $DBGroup = 'default'; // ICCT DB
    protected $table = 'SubSystemInfo';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    public function getLatestByDevice(string $device)
    {

 $result = $this->db
                   ->query("CALL GetLatestDeviceSNMP(?)", [$device])
                   ->getResultArray();

    $this->db->close(); // VERY IMPORTANT for stored procedures

    return $result;
    }
}
