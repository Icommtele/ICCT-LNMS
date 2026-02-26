/*<!DOCTYPE html>
<html>
<head>
    <title>LNMS Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

<style>
@keyframes blinkRed {
    0%,100% { box-shadow: 0 0 0px red; }
    50% { box-shadow: 0 0 14px red; }
}
.blink-down { animation: blinkRed 1s infinite; }

.menu-item {
    display: block;
    padding: 8px 14px;
    border-radius: 10px;
    transition: all .2s;
    color: #d1fae5;
    font-size: .95rem;
}
.menu-item:hover {
    background: linear-gradient(90deg,#064e3b,#047857);
    transform: translateX(4px);
}
.menu-item.active {
    background: linear-gradient(90deg,#047857,#10b981);
    font-weight: 600;
}
</style>
</head>

<body class="bg-[#f5f7fa]">

<div class="flex h-screen">

<!-- LEFT SIDEBAR -->
<div class="w-72 bg-emerald-950 text-white flex flex-col">

    <!-- SAME HEIGHT AS TOP BAR -->
    <div class="h-16 px-6 flex items-center text-2xl font-bold border-b border-emerald-800">
        LNMS
    </div>

    <ul class="flex-1 px-4 py-6 space-y-1">
        <li><a class="menu-item active" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
        <li><a class="menu-item" href="#">Configuration</a></li>
        <li><a class="menu-item" href="#">Fault Management</a></li>
        <li><a class="menu-item" href="#">Actions</a></li>
        <li><a class="menu-item" href="#">Escalation</a></li>
        <li><a href="<?= base_url('graphs') ?>"  class="menu-item" href="#">Graphs</a></li>
        <li><a href="<?= base_url('reports') ?>"  class="menu-item" href="#">Reports</a></li>
        <li><a class="menu-item" href="#">Diagnostics</a></li>
        <li><a class="menu-item" href="#">Chat</a></li>
        <li><a class="menu-item" href="#">Inventory</a></li>
        <li><a class="menu-item" href="#">Topology</a></li>
        <li><a class="menu-item" href="#">Users</a></li>
        <li><a class="menu-item" href="#">Audit Logs</a></li>
    </ul>
</div>

<!-- RIGHT AREA -->
<div class="flex-1 flex flex-col">

<!-- TOP BAR (FIXED HEIGHT) -->
<div class="h-16 bg-white flex items-center justify-between px-8 border-b">

    <!-- Welcome text -->
    <div class="text-lg font-semibold text-gray-800">
        Welcome <?= esc(session()->get('username')) ?>
    </div>

    <!-- User icon -->
    <div class="relative">
        <button id="userBtn" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-full bg-emerald-600 text-white
                        flex items-center justify-center font-bold">
                <?= strtoupper(substr(session()->get('username'),0,1)) ?>
            </div>
        </button>

        <div id="userMenu"
             class="hidden absolute right-0 mt-2 w-36 bg-white shadow rounded border">
            <a href="<?= base_url('logout') ?>"
               class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                Logout
            </a>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="flex-1 p-8 overflow-auto">

    <!-- PAGE NAME -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-500">Overall device health & live status</p>
    </div>

    <!-- DEVICE CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <?php foreach ($devices as $d): ?>
        <?php
            $isUp = ($d['status'] == 3);
            $desc = strtolower($d['description']);
            $type = str_contains($desc,'generator') || str_contains($desc,'dg')
                    ? 'Diesel Generator'
                    : (str_contains($desc,'ups') ? 'UPS' : 'Device');
        ?>

        <a href="<?= base_url('device/' . urlencode($d['description'])) ?>">
            <div id="device-<?= $d['id'] ?>"
                 class="rounded-xl p-5 border-l-8 shadow-md hover:shadow-xl transition
                 <?= $isUp ? 'border-green-700 bg-green-50'
                           : 'border-red-700 bg-red-50 blink-down' ?>">

                <h2 class="text-lg font-semibold mb-2"><?= esc($type) ?></h2>

                <p class="text-sm"><b>Name:</b> <?= esc($d['description']) ?></p>
                <p class="text-sm"><b>Host:</b> <?= esc($d['hostname']) ?></p>
                <p class="text-sm"><b>Location:</b> <?= esc($d['snmp_sysLocation'] ?? '-') ?></p>

                <p class="text-sm mt-2 font-bold device-status
                    <?= $isUp ? 'text-green-700' : 'text-red-700' ?>">
                    Status: <?= $isUp ? 'UP' : 'DOWN' ?>
                </p>
            </div>
        </a>
    <?php endforeach; ?>

    </div>
</div>
</div>
</div>

<script>
document.getElementById('userBtn').onclick = () =>
    document.getElementById('userMenu').classList.toggle('hidden');
</script>

</body>
</html>

*/




<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- DEVICE GRID -->
<div class="grid grid-cols-1 -mt-4  sm:grid-cols-2 lg:grid-cols-4 gap-6">

<?php foreach ($devices as $d): ?>
    <?php $isUp = ($d['status'] == 3); ?>

    <a href="<?= base_url('device/' . urlencode($d['description'])) ?>">

<!-- DEVICE CARD -->
<div
    id="device-<?= $d['id'] ?>"
    class="group rounded-lg p-5 transition-all duration-300
           bg-white text-slate-800 border border-slate-200
           shadow-[0_6px_18px_rgba(0,0,0,0.18)]
           hover:bg-gradient-to-br from-[#0F2A5A] via-[#143580] to-[#2E5FA8]
           hover:text-white
           hover:shadow-[0_10px_28px_rgba(0,0,0,0.25)]">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold truncate
                   text-slate-800 group-hover:text-white">
            <?= esc($d['description']) ?>
        </h2>

        <span class="text-slate-400 group-hover:text-white/80 text-sm">
            ▢
        </span>
    </div>

    <!-- DETAILS -->
    <p class="text-sm text-slate-600 group-hover:text-white/90">
        <strong>Host:</strong> <?= esc($d['hostname']) ?>
    </p>

    <p class="text-sm text-slate-600 group-hover:text-white/90">
        <strong>Location:</strong>
        <?= esc($d['snmp_sysLocation'] ?? '-') ?>
    </p>

    <!-- STATUS BADGE (UNCHANGED COLOR) -->
    <div class="mt-4">
        <span
            class="device-status inline-flex items-center px-3 py-1
                   text-xs font-semibold rounded-full
                   <?= $isUp ? 'bg-emerald-600' : 'bg-red-600' ?> text-white">
            <?= $isUp ? 'UP' : 'DOWN' ?>
        </span>
    </div>

</div>


    </a>

<?php endforeach; ?>

</div>

<!-- AUTO REFRESH SCRIPT -->
<script>
function refreshDashboard() {
    fetch("<?= base_url('dashboard/status') ?>")
        .then(res => res.json())
        .then(data => {
            data.forEach(d => {
                const card = document.getElementById('device-' + d.id);
                if (!card) return;

                const statusBadge = card.querySelector('.device-status');

                if (d.status == 3) {
                    statusBadge.textContent = 'UP';
                    statusBadge.className =
                        'device-status inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-emerald-600 text-white';
                } else {
                    statusBadge.textContent = 'DOWN';
                    statusBadge.className =
                        'device-status inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-600 text-white';
                }
            });
        })
        .catch(err => console.error(err));
}

// Refresh every 10 seconds
setInterval(refreshDashboard, 10000);
</script>

<?= $this->endSection() ?>


