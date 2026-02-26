<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Graphs extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /* MAIN PAGE */
    public function index()
    {
        return view('graphs/index', [
            'active' => 'graphs',
            'title'  => 'Graphs'
        ]);
    }

    /* DROPDOWNS (REUSED FROM REPORTS) */

    public function devices()
    {
        return $this->response->setJSON(
            $this->db->query("
                SELECT DISTINCT device_description
                FROM DG_SNMPData
                ORDER BY device_description
            ")->getResultArray()
        );
    }

    public function sections($device)
    {
        return $this->response->setJSON(
            $this->db->query("
                SELECT DISTINCT Section
                FROM DG_SNMPData
                WHERE device_description = ?
                ORDER BY Section
            ", [$device])->getResultArray()
        );
    }

    public function parameters($device, $section)
    {
        return $this->response->setJSON(
            $this->db->query("
                SELECT DISTINCT parameter
                FROM DG_SNMPData
                WHERE device_description = ?
                  AND Section = ?
                ORDER BY parameter
            ", [$device, $section])->getResultArray()
        );
    }

    /* GRAPH DATA (AJAX) */

    public function data()
    {
        $device    = $this->request->getGet('device');
        $section   = $this->request->getGet('section');
        $parameter = $this->request->getGet('parameter');
        $mode      = $this->request->getGet('mode');
        $period    = $this->request->getGet('period');
        $from      = $this->request->getGet('from');
        $to        = $this->request->getGet('to');

        $where  = ["device_description = ?"];
        $params = [$device];

        if ($section !== 'ALL') {
            $where[] = "Section = ?";
            $params[] = $section;
        }

        if ($parameter !== 'ALL') {
            $where[] = "parameter = ?";
            $params[] = $parameter;
        }

        if ($mode === 'period') {
            if ($period === 'today') {
                $where[] = "DATE(ts) = CURDATE()";
            } elseif ($period === 'month') {
                $where[] = "MONTH(ts)=MONTH(CURDATE()) AND YEAR(ts)=YEAR(CURDATE())";
            } elseif ($period === 'year') {
                $where[] = "YEAR(ts)=YEAR(CURDATE())";
            }
        } else {
            $where[] = "ts BETWEEN ? AND ?";
            $params[] = $from;
            $params[] = $to;
        }

        $sql = "
            SELECT parameter, value
            FROM DG_SNMPData
            WHERE " . implode(' AND ', $where) . "
            ORDER BY ts DESC
            LIMIT 50
        ";

        $rows = $this->db->query($sql, $params)->getResultArray();

        $labels = [];
        $values = [];

        foreach ($rows as $r) {
            if (is_numeric($r['value'])) {
                $labels[] = $r['parameter'];
                $values[] = (float)$r['value'];
            }
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'values' => $values
        ]);
    }
}
