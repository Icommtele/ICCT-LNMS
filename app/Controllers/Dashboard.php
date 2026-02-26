<?php

namespace App\Controllers;

class Dashboard extends BaseController
{

public function index()
    {
        $db = \Config\Database::connect('cacti');

        $devices = $db->query("CALL GetDashboardDevices()")->getResultArray();

        return view('dashboard', [
            'devices' => $devices,
            'active'  => 'dashboard',   // IMPORTANT for sidebar highlight
            'title'   => 'Dashboard'
        ]);
    }

    // AUTO REFRESH API
    public function status()
    {
        $db = \Config\Database::connect('cacti');

$result=$db->query("CALL GetDeviceStatus() ")->getResultArray();

       
 return $this->response->setJSON($result);
    }

}
