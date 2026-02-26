<?php

namespace App\Controllers;

use App\Models\DGSnmpModel;

class Device extends BaseController
{
    public function details($device)
    {
        // Decode URL (Diesel%20Generator → Diesel Generator)
        $device = urldecode($device);

        // Load existing model (NO new model)
        $model = new DGSnmpModel();

        // Get latest SNMP values per parameter
        $data = $model->getLatestByDevice($device);

        // 🔴 SAFETY: if no data, still send empty array
        if (!$data) {
            $data = [];
        }

        return view('device_details', [
            'device' => $device,
            'data'   => $data   // ✅ THIS WAS MISSING
        ]);
    }
}
