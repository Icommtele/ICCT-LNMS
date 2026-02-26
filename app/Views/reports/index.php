<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- ================= TOP FILTER ROW ================= -->
<div class="filter-row top-filter">

    <!-- Report Type -->
    <div class="filter-box">
        <label>Report Type</label>
        <select id="reportType" onchange="onReportTypeChange()">
            <option value="">-- Select --</option>
            <option value="Daily">Daily</option>
            <option value="Weekly">Weekly</option>
            <option value="Monthly">Monthly</option>
            <option value="Quarter">Quarter</option>
            <option value="Year">Year</option>
        </select>
    </div>

    <!-- DAILY -->
    <div id="DailyPanel" class="filter-panel-inline">
        <div class="filter-box">
            <label>From</label>
            <input type="date" id="dailyFrom">
        </div>
        <div class="filter-box">
            <label>To</label>
            <input type="date" id="dailyTo">
        </div>
        <div class="filter-box">
            <label>Device</label>
            <select id="dailyDevice"></select>
        </div>
        <button class="apply-btn" onclick="applyFilter('Daily')">Apply</button>
    </div>

    <!-- WEEKLY -->
    <div id="WeeklyPanel" class="filter-panel-inline">
        <div class="filter-box">
            <label>Year</label>
            <select id="weeklyYear"></select>
        </div>
        <div class="filter-box">
            <label>Month</label>
            <select id="weeklyMonth" onchange="generateWeeks()"></select>
        </div>
        <div class="filter-box">
            <label>Week</label>
            <select id="weeklyWeek"></select>
        </div>
        <div class="filter-box">
            <label>Device</label>
            <select id="weeklyDevice"></select>
        </div>
        <button class="apply-btn" onclick="applyFilter('Weekly')">Apply</button>
    </div>

    <!-- MONTHLY -->
    <div id="MonthlyPanel" class="filter-panel-inline">
        <div class="filter-box">
            <label>Year</label>
            <select id="monthlyYear"></select>
        </div>
        <div class="filter-box">
            <label>Month</label>
            <select id="monthlyMonth"></select>
        </div>
        <div class="filter-box">
            <label>Device</label>
            <select id="monthlyDevice"></select>
        </div>
        <button class="apply-btn" onclick="applyFilter('Monthly')">Apply</button>
    </div>

    <!-- QUARTER -->
    <div id="QuarterPanel" class="filter-panel-inline">
        <div class="filter-box">
            <label>Year</label>
            <select id="quarterYear"></select>
        </div>
        <div class="filter-box">
            <label>Quarter</label>
            <select id="quarterSelect">
                <option value="1">Q1</option>
                <option value="2">Q2</option>
                <option value="3">Q3</option>
                <option value="4">Q4</option>
            </select>
        </div>
        <div class="filter-box">
            <label>Device</label>
            <select id="quarterDevice"></select>
        </div>
        <button class="apply-btn" onclick="applyFilter('Quarter')">Apply</button>
    </div>

    <!-- YEAR -->
    <div id="YearPanel" class="filter-panel-inline">
        <div class="filter-box">
            <label>Year</label>
            <select id="yearOnly"></select>
        </div>
        <div class="filter-box">
            <label>Device</label>
            <select id="yearDevice"></select>
        </div>
        <button class="apply-btn" onclick="applyFilter('Year')">Apply</button>
    </div>

</div>


<!-- ================= TABLE RESULT ================= -->
<div id="reportTableContainer" style="margin-top:20px;"></div>
<div id="paginationContainer" style="margin-top:10px;"></div>

<style>
.reports-container {
    padding: 10px 0;
}

.page-title {
    margin-bottom: 15px;
    color: #143580;
    font-weight: 600;
}

.filter-card {
    background: #fff;
    padding: 18px;
    border-radius: 14px;
    margin-bottom: 16px;
    box-shadow:
        0 6px 18px rgba(0,0,0,0.08),
        0 2px 6px rgba(0,0,0,0.05);
}

.top-filter {
    margin-top: -25px;
}

.filter-row {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-box {
    min-width: 180px;
}

.filter-box label {
    font-size: 13px;
    font-weight: 600;
    color: #143580;
    margin-bottom: 6px;
    display: block;
}

.filter-box input,
.filter-box select {
    width: 100%;
    height: 36px;
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
}

.apply-btn {
    height: 36px;
    padding: 0 18px;
    background: linear-gradient(to right,#0F2A5A,#143580,#2E5FA8);
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}

.apply-btn:hover {
    transform: translateY(-1px);
}

.filter-panel-inline {
    display: none;
    flex-direction: row;
    gap: 18px;
    align-items: flex-end;
}

.table-container table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}
.table-container th {
    background: #143580;
    color: #fff;
    padding: 10px;
    font-size: 13px;
}

.table-container td {
    padding: 8px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 13px;
}

.pagination-container {
    margin-top: 12px;
}

.pagination-container button {
    padding: 6px 12px;
    margin-right: 5px;
    border: none;
    background: #143580;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}



.report-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}
.report-table thead {
    background: linear-gradient(to right,#0F2A5A,#143580,#2E5FA8);
}

.report-table th {
    padding: 12px;
    color: #fff;
    font-size: 16px;
    text-align: center;
}

.report-table td {
    padding: 10px;
    font-size: 13px;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
}

.report-table tbody tr:hover {
    background: #f1f5f9;
}

</style>
<script>

let currentPage = 1;
let lastFilters = {};

// ================= PANEL SWITCH =================
function onReportTypeChange() {

    const panels = ['Daily','Weekly','Monthly','Quarter','Year'];

    panels.forEach(p => {
        document.getElementById(p + 'Panel').style.display = 'none';
    });

    const type = document.getElementById('reportType').value;

    if (type) {
        document.getElementById(type + 'Panel').style.display = 'flex';
    }
}

// ================= LOAD DEVICES =================
async function loadDevices() {
    const response = await fetch("<?= base_url('reports/getDevices') ?>");
    const devices = await response.json();

    const selects = document.querySelectorAll("select[id$='Device']");
    selects.forEach(select => {
        select.innerHTML = "<option value=''>Select Device</option>";
        devices.forEach(d => {
            select.innerHTML += `<option value="${d.device_description}">${d.device_description}</option>`;
        });
    });
}

// ================= YEAR + MONTH INIT =================
function initYearMonth() {
    const yearSelects = document.querySelectorAll("select[id*='Year']");
    const currentYear = new Date().getFullYear();

    yearSelects.forEach(sel => {
        for (let y = currentYear; y >= 2020; y--) {
            sel.innerHTML += `<option value="${y}">${y}</option>`;
        }
    });
 const monthSelects = document.querySelectorAll("select[id*='Month']");
    monthSelects.forEach(sel => {
        for (let m = 1; m <= 12; m++) {
            sel.innerHTML += `<option value="${m}">${m}</option>`;
        }
    });
}

// ================= WEEK GENERATOR =================
function generateWeeks() {
    const year = document.getElementById("weeklyYear").value;
    const month = document.getElementById("weeklyMonth").value;
    const weekSelect = document.getElementById("weeklyWeek");

    weekSelect.innerHTML = "";

    const lastDay = new Date(year, month, 0).getDate();
    let weekNumber = 1;

    for (let day = 1; day <= lastDay; day += 7) {
        let endDay = day + 6;
        if (endDay > lastDay) endDay = lastDay;

        weekSelect.innerHTML += `<option value="${weekNumber}">
            Week ${weekNumber} (${day}-${endDay})
        </option>`;

        weekNumber++;
    }
}

// ================= APPLY FILTER =================
async function applyFilter(type, page = 1) {

    currentPage = page;

    let formData = new FormData();
    formData.append("report_type", type);
    formData.append("page", page);

    if (type === "Daily") {
 if (!dailyFrom.value || !dailyTo.value || !dailyDevice.value) {
            alert("Please select all Daily fields");
            return;
        }

        formData.append("from", dailyFrom.value);
        formData.append("to", dailyTo.value);
        formData.append("device", dailyDevice.value);
    }

    if (type === "Weekly") {

        if (!weeklyYear.value || !weeklyMonth.value || !weeklyWeek.value || !weeklyDevice.value) {
            alert("Please select all Weekly fields");
            return;
        }

        formData.append("year", weeklyYear.value);
        formData.append("month", weeklyMonth.value);
        formData.append("week", weeklyWeek.value);
        formData.append("device", weeklyDevice.value);
    }

    if (type === "Monthly") {

        if (!monthlyYear.value || !monthlyMonth.value || !monthlyDevice.value) {
            alert("Please select all Monthly fields");
            return;
        }

        formData.append("year", monthlyYear.value);
        formData.append("month", monthlyMonth.value);
        formData.append("device", monthlyDevice.value);
    }

    if (type === "Quarter") {

        if (!quarterYear.value || !quarterSelect.value || !quarterDevice.value) {
            alert("Please select all Quarter fields");
            return;
        }
 formData.append("year", quarterYear.value);
        formData.append("quarter", quarterSelect.value);
        formData.append("device", quarterDevice.value);
    }

    if (type === "Year") {

        if (!yearOnly.value || !yearDevice.value) {
            alert("Please select all Year fields");
            return;
        }

        formData.append("year", yearOnly.value);
        formData.append("device", yearDevice.value);
    }

    const response = await fetch("<?= base_url('reports/getReportData') ?>", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    renderTable(result.data);
    renderPagination(result.pages);
}
// ================= RENDER TABLE =================
function renderTable(data) {

    if (!data || data.length === 0) {
        reportTableContainer.innerHTML = "<p>No data found.</p>";
        return;
    }

    let html = `
        <div class="table-top-bar">
            <input type="text" id="paramSearch"
                   placeholder="Search Parameter..."
                   onkeyup="filterTable()"
                   class="search-box">

            <div>
                <button onclick="exportRaw(null)" class="export-btn">
                    Export All Raw Data (CSV)
                </button>
            </div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Parameter</th>
                    <th>Average</th>
                    <th>Maximum</th>
                    <th>Minimum</th>
                    <th>Count</th>
                    <th>Export</th>
                </tr>
            </thead>
            <tbody>
    `;

    data.forEach(row => {
        html += `
            <tr>
                <td>${row.device_description}</td>
                <td>${row.parameter}</td>
 <td>${parseFloat(row.avg_value).toFixed(2)}</td>
                <td>${row.max_value}</td>
                <td>${row.min_value}</td>
                <td>${row.total_records}</td>
                <td>
                    <button onclick="exportRaw('${row.parameter}')"
                            class="row-export-btn">
                        Export
                    </button>
                </td>
            </tr>
        `;
    });

    html += "</tbody></table>";

    reportTableContainer.innerHTML = html;
}





function filterTable() {

    const input = document.getElementById("paramSearch").value.toLowerCase();
    const rows = document.querySelectorAll(".report-table tbody tr");

    rows.forEach(row => {
        const param = row.children[1].innerText.toLowerCase();
        row.style.display = param.includes(input) ? "" : "none";
    });
}
function exportRaw(parameter) {

    const type = document.getElementById("reportType").value;
    let formData = new FormData();

    formData.append("report_type", type);

    if (parameter)
        formData.append("parameter", parameter);

    // DAILY
    if (type === "Daily") {
        formData.append("from", dailyFrom.value);
        formData.append("to", dailyTo.value);
        formData.append("device", dailyDevice.value);
    }

    // WEEKLY
    if (type === "Weekly") {
        formData.append("year", weeklyYear.value);
        formData.append("month", weeklyMonth.value);
        formData.append("week", weeklyWeek.value);
        formData.append("device", weeklyDevice.value);
    }

    // MONTHLY
    if (type === "Monthly") {
        formData.append("year", monthlyYear.value);
        formData.append("month", monthlyMonth.value);
        formData.append("device", monthlyDevice.value);
    }

    // QUARTER
    if (type === "Quarter") {
        formData.append("year", quarterYear.value);
        formData.append("quarter", quarterSelect.value);
        formData.append("device", quarterDevice.value);
    }
 // YEAR
    if (type === "Year") {
        formData.append("year", yearOnly.value);
        formData.append("device", yearDevice.value);
    }

    fetch("<?= base_url('reports/exportRaw') ?>", {
        method: "POST",
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {

        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
a.download = parameter
    ? parameter + "_raw_data.csv"
    : "all_raw_data.csv";

        a.click();

        window.URL.revokeObjectURL(url);
    });
}
// ================= PAGINATION =================
function renderPagination(totalPages) {

    let html = "";

    for (let i = 1; i <= totalPages; i++) {
        html += `<button onclick="applyFilter(document.getElementById('reportType').value, ${i})">
                    ${i}
                 </button> `;
    }

    paginationContainer.innerHTML = html;
}

// ================= INIT =================
document.addEventListener("DOMContentLoaded", function() {
    loadDevices();
    initYearMonth();
});

</script>

<?= $this->endSection() ?>  
