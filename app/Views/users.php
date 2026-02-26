<?= $this->extend('layouts/main') ?>  
<?= $this->section('content') ?>


<?php if (session()->getFlashdata('success')): ?>
<div class="bg-green-100 p-3 rounded mb-4">
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-100 p-3 rounded mb-4">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<!-- <button onclick="toggleCreateForm()"
        class="px-4 py-2 rounded-lg text-white font-medium transition-all duration-300
               bg-gradient-to-br from-[#0F2A5A] via-[#143580] to-[#2E5FA8] -mt-4
               hover:from-[#C24A1A] hover:via-[#E15A1D] hover:to-[#F07A2A]">
    Add User
</button> -->

<button onclick="toggleCreateForm()"
        class="w-full flex items-center justify-between
               px-6 py-3 mb-4
               bg-white
               border border-slate-300
               rounded-md
               text-[#143178] font-semibold
               hover:bg-slate-50
               transition">

    <!-- LEFT TEXT -->
    <span>Add User</span>

    <!-- RIGHT ARROW -->
    <svg class="w-5 h-5 text-slate-600"
         xmlns="http://www.w3.org/2000/svg"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="M19 9l-7 7-7-7"/>
    </svg>
</button>



<!-- CREATE USER FORM -->
<div id="createUserForm" class="hidden bg-white p-6 rounded form-elevated mt-4  mb-4">
    <form method="post" action="<?= base_url('users/create') ?>">
        <label class="block mb-2">Username</label>
        <input name="username" class="border p-2 w-full mb-4" required>

        <label class="block mb-2">Password</label>
        <input type="password" name="password" class="border p-2 w-full mb-4" required>

        <label class="block mb-2">Role</label>
        <select name="role" class="border p-2 w-full mb-4" required>
            <option value="NetworkAdmin">Network Admin</option>
            <option value="SystemAdmin">System Admin</option>
            <option value="NetworkOperator">Network Operator</option>
        </select>

        <label class="block mb-2">Module Access</label>
        <?php $allModules = ['Dashboard','Reports','Graphs','Inventory','Topology','Users']; ?>
        <div class="mb-4">
            <?php foreach ($allModules as $m): ?>
                <label class="block">
                    <input type="checkbox" name="tabs[]" value="<?= $m ?>"> <?= $m ?>
                </label>
            <?php endforeach; ?>
        </div>

        <button class="px-4 py-2 rounded-lg text-white font-medium transition-all duration-300
               bg-gradient-to-br from-[#0F2A5A] via-[#143580] to-[#2E5FA8]
               hover:from-[#C24A1A] hover:via-[#E15A1D] hover:to-[#F07A2A]">Create User</button>
        <button type="button" onclick="toggleCreateForm()" class="ml-3 underline text-gray-600">
            Cancel
        </button>
    </form>
</div>

<!-- USERS LIST -->
<h3 class="text-xl font-bold mt-4  mb-4">Existing Users</h3>

<?php foreach ($users as $u): ?>
<div class="user-card">

    <div class="flex items-end gap-4">

        <!-- USERNAME -->
        <div class="flex-1">
            <label class="text-xs text-gray-500">Username</label>
            <input value="<?= esc($u['username']) ?>" disabled
                   class="w-full border rounded p-2 bg-gray-100">
        </div>

        <!-- ROLE -->
        <div class="flex-1">
            <label class="text-xs text-gray-500">Role</label>
<select name="role"
        class="editable-<?= $u['id'] ?> w-full border rounded p-2"
        disabled>

                <option value="NetworkAdmin" <?= $u['role']=='NetworkAdmin'?'selected':'' ?>>NetworkAdmin</option>
                <option value="SystemAdmin" <?= $u['role']=='SystemAdmin'?'selected':'' ?>>SystemAdmin</option>
                <option value="NetworkOperator" <?= $u['role']=='NetworkOperator'?'selected':'' ?>>NetworkOperator</option>
            </select>
        </div>

        <!-- MODULES -->
        <div class="flex-1 relative">
            <label class="text-xs text-gray-500">Modules</label>
<div onclick="toggleModules(<?= $u['id'] ?>)"
     class="editable-<?= $u['id'] ?> w-full border rounded p-2 bg-white cursor-pointer
            flex justify-between"
     data-disabled="true">
                <span>Select Modules</span><span>▾</span>
            </div>

            <div id="modules-<?= $u['id'] ?>"
                 class="hidden absolute left-0 right-0 bg-white border rounded shadow p-3 mt-1 z-20">
                <?php foreach ($allModules as $m): ?>
                    <label class="block text-sm">
<input type="checkbox"
       class="editable-<?= $u['id'] ?>"
       name="tabs[]"
       value="<?= $m ?>"
       <?= in_array($m, $u['modules']) ? 'checked' : '' ?>
       disabled>

                        <?= $m ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="flex gap-2 flex-1">


<form method="post"
      action="<?= base_url('users/update/'.$u['id']) ?>"
      class="inline-block">

    <button type="button"
            onclick="toggleEdit(<?= $u['id'] ?>)"
            title="Modify User"
            class="action-btn">

        <!-- Pencil icon -->
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.125 19.588a4.5 4.5 0 01-1.897 1.13l-3.22 1.072a.75.75 0 01-.948-.948l1.072-3.22a4.5 4.5 0 011.13-1.897L16.862 3.487z"/>
        </svg>
    </button>

</form>


<form method="post"
      action="<?= base_url('users/delete/'.$u['id']) ?>"
      onsubmit="return confirm('Delete this user?');"
      style="display:inline-block;">

    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">

    <button type="submit"
            class="action-btn"
            title="Delete User">

        <!-- Trash icon (matches your design) -->
        <svg xmlns="http://www.w3.org/2000/svg"
             width="20"
             height="20"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">

            <!-- Lid -->
            <path d="M3 6h18"/>
            <path d="M8 6V4h8v2"/>

            <!-- Bin body -->
            <path d="M6 6v14a2 2 0 002 2h8a2 2 0 002-2V6"/>

            <!-- Vertical lines -->
            <path d="M10 11v6"/>
            <path d="M14 11v6"/>

        </svg>

    </button>
</form>


        </div>
    </div>

</div>
<?php endforeach; ?>

<!-- JS -->
<script>
function toggleCreateForm() {
    document.getElementById('createUserForm').classList.toggle('hidden');
}

function toggleModules(id) {
    const container = document.getElementById('modules-' + id);
    const trigger = container.previousElementSibling;

    if (trigger.getAttribute('data-disabled') === 'true') return;

    container.classList.toggle('hidden');
}

</script>

<style>
.action-btn {
    position: relative;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #143580; /* default icon color */
    transition: color 0.25s ease;
}

/* underline */
.action-btn::after {
    content: "";
    position: absolute;
    bottom: 2px;
    left: 20%;
    width: 60%;
    height: 2px;
    border-radius: 2px;
    background: linear-gradient(
        to right,
        #0F2A5A,
        #143580,
        #2E5FA8
    );
    opacity: 0.7;
    transition: background 0.25s ease, opacity 0.25s ease;
}

/* hover: SAME color for icon + underline */
.action-btn:hover {
    color: #C24A1A; /* icon color on hover */
}

.action-btn:hover::after {
    background: linear-gradient(
        to right,
        #C24A1A,
        #E15A1D,
        #F07A2A
    );
    opacity: 1;
}
</style>


<style>
.form-elevated {
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.16),
        0 2px 6px rgba(0, 0, 0, 0.12);
}
</style>

<style>
.user-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 16px;

    /* visible, soft, all-sides shadow */
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.16),
        0 2px 6px rgba(0, 0, 0, 0.12);
}
</style>


<script>
function toggleEdit(userId) {
    const fields = document.querySelectorAll('.editable-' + userId);
    const btn = document.getElementById('btn-' + userId);

    const isEditMode = btn.innerText === 'Modify';

    fields.forEach(el => {
        if (isEditMode) {
            el.removeAttribute('disabled');
            el.classList.remove('opacity-60');
        } else {
            el.setAttribute('disabled', true);
            el.classList.add('opacity-60');
        }
    });

    if (isEditMode) {
        btn.innerText = 'Save';
        btn.type = 'submit';
        btn.classList.remove('bg-emerald-950');
        btn.classList.add('bg-emerald-700');
    } else {
        btn.innerText = 'Modify';
        btn.type = 'button';
        btn.classList.remove('bg-emerald-700');
        btn.classList.add('bg-emerald-950');
    }
}


</script>


<?= $this->endSection() ?>
