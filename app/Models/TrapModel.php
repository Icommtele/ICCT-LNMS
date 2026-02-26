<?php

class TrapModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Get last processed syslog seq
    public function getLastSeq()
    {
        $stmt = $this->db->query("SELECT MAX(syslog_seq) as max_seq FROM lnms_traps");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['max_seq'] ?? 0;
    }

    // Get new traps from cacti.syslog
    public function fetchNewSyslogTraps($lastSeq)
    {
        $stmt = $this->db->prepare("
            SELECT seq, logtime, message
            FROM syslog
            WHERE seq > :lastSeq
            AND message LIKE '%snmpTrapOID%'
            ORDER BY seq ASC
        ");
        $stmt->execute(['lastSeq' => $lastSeq]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert into structured table
    public function insertTrap($data)
    {
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO lnms_traps
            (syslog_seq, logtime, source_ip, trap_oid, raw_message)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['seq'],
            $data['logtime'],
            $data['source_ip'],
            $data['trap_oid'],
            $data['message']
        ]);
    }

    // Get latest traps for UI
    public function getLatestTraps()
    {
        $stmt = $this->db->query("
            SELECT * FROM lnms_traps
            ORDER BY logtime DESC
            LIMIT 20
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
