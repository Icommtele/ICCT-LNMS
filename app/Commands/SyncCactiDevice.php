<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncCactiDevice extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'cacti:sync';
    protected $description = 'Sync ICCT devices to Cacti';

    public function run(array $params)
    {
        $icctDB  = \Config\Database::connect();
        $cactiDB = \Config\Database::connect('cacti');

        $devices = $icctDB->table('lnms_devices')->get()->getResultArray();

        foreach ($devices as $device) {

            // Check if already exists in Cacti
            $exists = $cactiDB->table('host')
                ->where('hostname', $device['ip'])
                ->countAllResults();

            if ($exists > 0) {
                CLI::write("Device {$device['ip']} already exists.", 'yellow');
                continue;
            }

            // Insert into Cacti host table
            $cactiDB->table('host')->insert([
                'description' => $device['name'],
                'hostname'    => $device['ip'],
                'snmp_community' => $device['community'],
                'snmp_version'   => 2,
                'status'         => 3
            ]);

            CLI::write("Device {$device['ip']} added to Cacti!", 'green');
        }
    }
}
