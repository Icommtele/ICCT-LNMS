<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ReportModel;

class Reports extends Controller
{
    protected $db;

    public function __construct()
    {


  $this->reportModel = new ReportModel();
    }

    /* ================= PAGE ================= */

    public function index()
    {
        return view('reports/index', [
            'active' => 'reports',
            'title'  => 'Reports'
        ]);
    }


/*
    ------------------------------------------------
    2️⃣ LOAD DEVICES (AJAX)
    ------------------------------------------------
    */
    public function getDevices()
    {
        $devices = $this->reportModel->getDevices();
        return $this->response->setJSON($devices);
    }

    /*
    ------------------------------------------------
    3️⃣ MAIN REPORT DATA (AJAX)
    ------------------------------------------------
    */
    public function getReportData()
    {
        $device      = $this->request->getPost('device');
        $reportType  = $this->request->getPost('report_type');

        $year     = $this->request->getPost('year');
        $month    = $this->request->getPost('month');
        $week     = $this->request->getPost('week');
        $quarter  = $this->request->getPost('quarter');
        $from     = $this->request->getPost('from');
        $to       = $this->request->getPost('to');

        $page     = (int) $this->request->getPost('page');
        $limit    = 20;
        $offset   = ($page - 1) * $limit;

        // Build date range
        $range = $this->buildDateRange(
            $reportType,
            $year,
            $month,
            $week,
            $quarter,
            $from,
            $to
        );

        // Call stored procedures
        $data = $this->reportModel->getAggregatedReport(
            $device,
            $range['from'],
            $range['to'],
            $limit,
            $offset
        );

        $total = $this->reportModel->getAggregatedReportCount(
            $device,
            $range['from'],
            $range['to']
        );

        return $this->response->setJSON([
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $limit)
        ]);
    }




public function exportRaw()
{
    $device = $this->request->getPost('device');
    $parameter = $this->request->getPost('parameter');

    $reportType = $this->request->getPost('report_type');
    $year = $this->request->getPost('year');
    $month = $this->request->getPost('month');
    $week = $this->request->getPost('week');
    $quarter = $this->request->getPost('quarter');
    $from = $this->request->getPost('from');
    $to = $this->request->getPost('to');

    $range = $this->buildDateRange(
        $reportType,
        $year,
        $month,
        $week,
        $quarter,
        $from,
        $to
    );

    $data = $this->reportModel->getRawExportData(
        $device,
        $parameter,
        $range['from'],
        $range['to']
    );

if (empty($data)) {
    die("No data found in export.");
}


    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="raw_export.csv"');

    $output = fopen("php://output", "w");

    fputcsv($output, [
        'Device',
        'Parameter',
        'Value',
        'Section',
        'RRD File',
        'Timestamp'
    ]);

    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}


    /*
    ------------------------------------------------
    4️⃣ DATE RANGE ENGINE (CORE LOGIC)
    ------------------------------------------------
    */
    private function buildDateRange($type, $year, $month, $week, $quarter, $from, $to)
    {
        switch ($type) {

            case 'Daily':
                return [
                    'from' => $from . ' 00:00:00',
                    'to'   => $to . ' 23:59:59'
                ];

            case 'Weekly':

                $startDay = (($week - 1) * 7) + 1;
                $endDay   = $startDay + 6;

                $lastDayOfMonth = date("t", strtotime("$year-$month-01"));

                if ($endDay > $lastDayOfMonth) {
                    $endDay = $lastDayOfMonth;
                }

                return [
                    'from' => "$year-$month-" . str_pad($startDay, 2, "0", STR_PAD_LEFT) . " 00:00:00",
                    'to'   => "$year-$month-" . str_pad($endDay, 2, "0", STR_PAD_LEFT) . " 23:59:59"
                ];

            case 'Monthly':

                $lastDay = date("t", strtotime("$year-$month-01"));

                return [
                    'from' => "$year-$month-01 00:00:00",
                    'to'   => "$year-$month-$lastDay 23:59:59"
                ];

            case 'Quarter':

                switch ($quarter) {
                    case 1:
                        $startMonth = 1;
                        break;
                    case 2:
                        $startMonth = 4;
                        break;
                    case 3:
                        $startMonth = 7;
                        break;
                    case 4:
                        $startMonth = 10;
                        break;
                }

                $endMonth = $startMonth + 2;

                return [
                    'from' => "$year-$startMonth-01 00:00:00",
                    'to'   => date("Y-m-t 23:59:59", strtotime("$year-$endMonth-01"))
                ];

            case 'Year':

                return [
                    'from' => "$year-01-01 00:00:00",
                    'to'   => "$year-12-31 23:59:59"
                ];
        }
    }

}
