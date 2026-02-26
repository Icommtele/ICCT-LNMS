<?php

namespace App\Controllers;

use App\Models\DGSnmpModel;

class Device extends BaseController
{
    public function details($deviceName)
    {
        $deviceName = urldecode($deviceName);

        $model = new DGSnmpModel();
        $rows  = $model->getLatestByDevice($deviceName);

        return view('device_details', [
            'device' => $deviceName,
            'rows'   => $rows
        ]);
    }
}
