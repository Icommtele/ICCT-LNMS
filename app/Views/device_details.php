<!DOCTYPE html>
<html>
<head>
    <title><?= esc($device) ?> - Device Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-6">


<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">
        <?= esc($device) ?> — Live Parameters
    </h1>

    <a href="<?= base_url('dashboard') ?>"
       class="text-sm text-blue-600 hover:underline">
        ← Back to Dashboard
    </a>
</div>

<!-- Filters -->
<div class="flex flex-wrap gap-4 mb-6">
    <!-- Section Filter -->
    <select id="sectionFilter"
        class="px-4 py-2 rounded border shadow text-sm">
        <option value="">All Sections</option>
        <?php
        $sections = array_unique(array_column($data, 'Section'));
        sort($sections);
        foreach ($sections as $sec):
        ?>
            <option value="<?= esc($sec) ?>">
                <?= esc($sec) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Search -->
    <input type="text"
        id="searchInput"
        placeholder="Search parameter..."
        class="px-4 py-2 rounded border shadow text-sm w-64">
</div>

<!-- Parameter Cards -->
<div id="cards"
     class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

<?php foreach ($data as $r): ?>
    <div class="param-card bg-white p-4 rounded-xl shadow-md
                hover:shadow-lg transition"
         data-section="<?= esc($r['Section']) ?>"
         data-param="<?= strtolower($r['parameter']) ?>">

        <h3 class="font-semibold text-gray-700 text-sm">
            <?= esc($r['parameter']) ?>
        </h3>

        <p class="text-2xl font-bold text-emerald-700 mt-1">
            <?= esc($r['value']) ?>
        </p>

        <p class="text-xs text-gray-500 mt-2">
            Section: <?= esc($r['Section']) ?><br>
            Time: <?= esc($r['ts']) ?>
        </p>
    </div>
<?php endforeach; ?>

</div>

<!-- Filtering Script -->
<script>
const sectionFilter = document.getElementById('sectionFilter');
const searchInput   = document.getElementById('searchInput');
const cards         = document.querySelectorAll('.param-card');

function applyFilters() {
    const section = sectionFilter.value.toLowerCase();
    const search  = searchInput.value.toLowerCase();

    cards.forEach(card => {
        const cardSection = card.dataset.section.toLowerCase();
        const cardParam   = card.dataset.param;

        const sectionMatch =
            section === '' || cardSection === section;

        const searchMatch =
            cardParam.includes(search);

        card.style.display =
            sectionMatch && searchMatch ? 'block' : 'none';
    });
}

sectionFilter.addEventListener('change', applyFilters);
searchInput.addEventListener('keyup', applyFilters);
</script>

</body>
</html>
