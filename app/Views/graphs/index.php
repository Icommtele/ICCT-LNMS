<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- FILTER PANEL -->
<div class="bg-white p-4 rounded-lg shadow mb-6 grid grid-cols-1 md:grid-cols-7 gap-4">

    <div>
        <label class="text-sm font-semibold">Mode</label>
        <select id="mode" class="w-full border rounded px-2 py-1" onchange="toggleMode()">
            <option value="period" selected>Time Period</option>
            <option value="range">Date Range</option>
        </select>
    </div>

    <div id="periodBox">
        <label class="text-sm font-semibold">Time Period</label>
        <select id="period" class="w-full border rounded px-2 py-1">
            <option value="today" selected>Today</option>
            <option value="month">Month</option>
            <option value="year">Year</option>
        </select>
    </div>

    <div id="fromBox" class="hidden">
        <label class="text-sm font-semibold">From</label>
        <input type="datetime-local" id="from" class="w-full border rounded px-2 py-1">
    </div>

    <div id="toBox" class="hidden">
        <label class="text-sm font-semibold">To</label>
        <input type="datetime-local" id="to" class="w-full border rounded px-2 py-1">
    </div>

    <div>
        <label class="text-sm font-semibold">Device</label>
        <select id="device" class="w-full border rounded px-2 py-1" onchange="loadSections()"></select>
    </div>

    <div>
        <label class="text-sm font-semibold">Section</label>
        <select id="section" class="w-full border rounded px-2 py-1" onchange="loadParameters()"></select>
    </div>

    <div>
        <label class="text-sm font-semibold">Parameter</label>
        <select id="parameter" class="w-full border rounded px-2 py-1"></select>
    </div>

    <div class="flex items-end">
        <button onclick="loadGraphs()"
            class="px-10 py-2 rounded-lg bg-emerald-900 text-white font-semibold hover:bg-emerald-800">
            Apply
        </button>
    </div>
</div>

<!-- GRAPHS -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Pie Chart</h3>
        <canvas id="pie"></canvas>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Bar Chart</h3>
        <canvas id="bar"></canvas>
    </div>

    <div class="bg-white p-4 rounded shadow md:col-span-2">
        <h3 class="font-semibold mb-2">Line Chart</h3>
        <canvas id="line"></canvas>
    </div>
</div>

<script>
let pieChart, barChart, lineChart;

const device    = document.getElementById('device');
const section   = document.getElementById('section');
const parameter = document.getElementById('parameter');
const mode      = document.getElementById('mode');
const period    = document.getElementById('period');
const from      = document.getElementById('from');
const to        = document.getElementById('to');

document.addEventListener('DOMContentLoaded', loadDevices);

function toggleMode() {
    const isPeriod = mode.value === 'period';
    document.getElementById('periodBox').classList.toggle('hidden', !isPeriod);
    document.getElementById('fromBox').classList.toggle('hidden', isPeriod);
    document.getElementById('toBox').classList.toggle('hidden', isPeriod);
}

/* ---------- DEVICES ---------- */
function loadDevices() {
    fetch('<?= base_url("graphs/devices") ?>')
    .then(r => r.json())
    .then(data => {
        device.innerHTML = '';
        data.forEach(d => {
            device.innerHTML += `
                <option value="${d.device_description}">
                    ${d.device_description}
                </option>`;
        });
        loadSections();
    });
}

/* ---------- SECTIONS ---------- */
function loadSections() {
    fetch(`<?= base_url("graphs/sections") ?>/${encodeURIComponent(device.value)}`)
    .then(r => r.json())
    .then(data => {
        section.innerHTML = `<option value="ALL">ALL</option>`;
        data.forEach(s => {
            section.innerHTML += `
                <option value="${s.Section}">
                    ${s.Section}
                </option>`;
        });
        loadParameters();
    });
}

/* ---------- PARAMETERS ---------- */
function loadParameters() {
    if (section.value === 'ALL') {
        parameter.innerHTML = `<option value="ALL">ALL</option>`;
        return;
    }

    fetch(`<?= base_url("graphs/parameters") ?>/${encodeURIComponent(device.value)}/${encodeURIComponent(section.value)}`)
    .then(r => r.json())
    .then(data => {
        parameter.innerHTML = `<option value="ALL">ALL</option>`;
        data.forEach(p => {
            parameter.innerHTML += `
                <option value="${p.parameter}">
                    ${p.parameter}
                </option>`;
        });
    });
}

/* ---------- LOAD GRAPHS ---------- */
function loadGraphs() {
    const url = new URL('<?= base_url("graphs/data") ?>');
    url.search = new URLSearchParams({
        device: device.value,
        section: section.value,
        parameter: parameter.value,
        mode: mode.value,
        period: period.value,
        from: from.value,
        to: to.value
    });

    fetch(url).then(r => r.json()).then(drawCharts);
}

/* ---------- DRAW CHARTS ---------- */
function drawCharts(data) {

    pieChart?.destroy();
    barChart?.destroy();
    lineChart?.destroy();

    pieChart = new Chart(document.getElementById('pie'), {
        type: 'pie',
        data: { labels: data.labels, datasets: [{ data: data.values }] }
    });

    barChart = new Chart(document.getElementById('bar'), {
        type: 'bar',
        data: { labels: data.labels, datasets: [{ data: data.values }] }
    });

    lineChart = new Chart(document.getElementById('line'), {
        type: 'line',
        data: { labels: data.labels, datasets: [{ data: data.values }] }
    });
}
</script>

<?= $this->endSection() ?>
