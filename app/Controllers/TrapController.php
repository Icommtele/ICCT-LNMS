<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TrapController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    { 
       $this->processNewTraps();
        $builder = $this->db->table('lnms_traps');
        $builder->orderBy('id', 'DESC');
        $builder->limit(20);

        $data = [
            'traps'  => $builder->get()->getResultArray(),
            'active' => 'traps',
            'title'  => 'SNMP Traps'
        ];

        return view('Traps', $data);
    }


private function processNewTraps()
{
    $icctDB  = \Config\Database::connect();
    $cactiDB = \Config\Database::connect('cacti');

    $lastSeqRow = $icctDB->table('lnms_traps')
        ->selectMax('syslog_seq')
        ->get()
        ->getRowArray();

    $lastSeq = $lastSeqRow['syslog_seq'] ?? 0;

    // 👇 UPDATE THIS QUERY ONLY
    $syslogs = $cactiDB->table('syslog')
        ->where('seq >', $lastSeq)
        ->like('message', 'snmpTrapOID')
        ->orderBy('seq', 'ASC')
        ->limit(50)
        ->get()
        ->getResultArray();

    foreach ($syslogs as $log) {

        preg_match('/UDP: \[(.*?)\]/', $log['message'], $sourceMatch);
        preg_match('/snmpTrapOID\.0 = OID: (.*?)#/', $log['message'], $trapMatch);
        preg_match('/INTEGER: (\d+)/', $log['message'], $valueMatch);

        $source_ip = isset($sourceMatch[1])
            ? explode(':', $sourceMatch[1])[0]
            : 'Unknown';

        $trap_oid = $trapMatch[1] ?? 'Unknown';
        $value    = $valueMatch[1] ?? null;

        $exists = $icctDB->table('lnms_traps')
            ->where('syslog_seq', $log['seq'])
            ->countAllResults();

        if ($exists > 0) {
            continue;
        }

        $icctDB->table('lnms_traps')->insert([
            'syslog_seq'  => $log['seq'],
            'logtime'     => $log['logtime'],
            'source_ip'   => $source_ip,
            'trap_oid'    => $trap_oid,
            'raw_message' => $log['message'],
            'value'       => $value
        ]);
    }
}


    public function refresh()
    {
        $builder = $this->db->table('lnms_traps');
        $builder->orderBy('id', 'DESC');
        $builder->limit(20);

        $traps = $builder->get()->getResultArray();

        foreach ($traps as $trap) {
            echo '
            <tr>
                <td class="px-6 py-4">'.esc($trap['source_ip']).'</td>
                <td class="px-6 py-4">'.esc($trap['trap_oid']).'</td>
                <td class="px-6 py-4">'.esc($trap['logtime']).'</td>
                <td class="px-6 py-4">'.esc($trap['value']).'</td>
            </tr>';
        }
    }
}
