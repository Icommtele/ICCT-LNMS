<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="w-full">

    <!-- Trap Table Card -->
    <div class="bg-white shadow-md   rounded-lg border border-slate-200">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-slate-600">

                <thead class="text-xs uppercase bg-slate-500 text-white">
                    <tr>
                        <th class="px-6 py-3">Device IP</th>
                        <th class="px-6 py-3">Trap Name</th>
                        <th class="px-6 py-3">Time</th>
                        <th class="px-6 py-3">Value</th>
                    </tr>
                </thead>

                <tbody id="trapList" class="divide-y divide-slate-200">

                    <?php if (!empty($traps)) : ?>
                        <?php foreach ($traps as $trap) : ?>

                            <tr class="hover:bg-slate-50 
                                <?= ($trap['value'] == 2) ? 'bg-red-100' : '' ?>">

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    <?= esc($trap['source_ip']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= esc($trap['trap_oid']) ?>
                                </td>

                                <td class="px-6 py-4">
                                    <?= date('d-m-Y H:i:s', strtotime($trap['logtime'])) ?>
                                </td>

                                <td class="px-6 py-4 font-semibold">
                                    <?= esc($trap['value'] ?? '-') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-slate-400">
                                No Traps Available
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>

</div>

<script>
setInterval(function() {
    fetch("<?= base_url('trap-refresh') ?>")
        .then(response => response.text())
        .then(data => {
            document.getElementById("trapList").innerHTML = data;
        });
}, 5000);
</script>

<?= $this->endSection() ?>
