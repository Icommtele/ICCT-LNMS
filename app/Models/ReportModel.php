<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{

protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /*
    ------------------------------------------
    1️⃣ GET DEVICES
    ------------------------------------------
    */
    public function getDevices()
    {
        $query = $this->db->query("CALL SP_GetDevices()");

        $result = $query->getResultArray();

        $query->freeResult();

        // VERY IMPORTANT in MariaDB
        while ($this->db->connID->more_results()) {
            $this->db->connID->next_result();
        }

        return $result;
    }

    /*
    ------------------------------------------
    2️⃣ GET PAGINATED AGGREGATED REPORT
    ------------------------------------------
    */
    public function getAggregatedReport($device, $from, $to, $limit, $offset)
    {
        $sql = "CALL SP_GetAggregatedReport(?, ?, ?, ?, ?)";

        $query = $this->db->query($sql, [
            $device,
            $from,
            $to,
            $limit,
            $offset
        ]);

        $result = $query->getResultArray();

        $query->freeResult();

        while ($this->db->connID->more_results()) {
            $this->db->connID->next_result();
        }

        return $result;
    }

    /*
    ------------------------------------------
    3️⃣ GET TOTAL COUNT (FOR PAGINATION)
    ------------------------------------------
    */
    public function getAggregatedReportCount($device, $from, $to)
    {
        $sql = "CALL SP_GetAggregatedReport_Count(?, ?, ?)";

        $query = $this->db->query($sql, [
            $device,
            $from,
            $to
        ]);

        $result = $query->getRowArray();

        $query->freeResult();

        while ($this->db->connID->more_results()) {
            $this->db->connID->next_result();
        }

        return $result['total_groups'] ?? 0;
    }

    /*
    ------------------------------------------
    4️⃣ EXPORT FULL DATA
    ------------------------------------------
    */
    public function getExportData($device, $from, $to)
    {
        $sql = "CALL SP_GetAggregatedReport_Export(?, ?, ?)";

        $query = $this->db->query($sql, [
            $device,
            $from,
            $to
        ]);

        $result = $query->getResultArray();

        $query->freeResult();

        while ($this->db->connID->more_results()) {
            $this->db->connID->next_result();
        }

        return $result;
    }


public function getRawExportData($device, $parameter, $from, $to)
{
    $sql = "CALL SP_GetRawDataForExport(?, ?, ?, ?)";

    $query = $this->db->query($sql, [
        $device,
        $parameter,
        $from,
        $to
    ]);

    $result = $query->getResultArray();

    $query->freeResult();

    while ($this->db->connID->more_results()) {
        $this->db->connID->next_result();
    }

    return $result;
}


}
