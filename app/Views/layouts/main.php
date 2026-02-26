<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'LNMS') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .sidebar-expanded { width: 12rem; }
        .sidebar-collapsed { width: 3.5rem; }

        #sidebar { transition: width 0.3s ease; }

        .menu-text { transition: opacity 0.2s ease; }
        .collapsed .menu-text { opacity: 0; display: none; }

        .sidebar-logo-img {
            height: 42px;
            transition: all 0.3s ease;
        }
        .collapsed .sidebar-logo-img { height: 36px; }

        .lnms-text {
            font-size: 20px;
            font-weight: 700;
            color: #143580;
            letter-spacing: 1px;
        }
        .collapsed .lnms-text { display: none; }

        .collapsed nav a {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
</head>

<body class="h-screen overflow-hidden bg-white">

<header class="h-14 bg-gradient-to-r
bg-white    border-slate-300 flex items-center justify-between px-6">
    <div class="flex items-center ml-1  gap-4">
        <img src="<?= base_url('assets/Icomm.PNG') ?>" class="h-10 ml-6 ">
        <span class="font-bold ml-7 text-xl ml-8  text-[#143178]  ">Local Network Management System</span>
    </div>

    <div class="relative">
        <button onclick="toggleUserMenu()" class="flex items-center gap-3 px-2 py-1 rounded-full hover:bg-slate-100">
            <div class="w-8 h-8 rounded-full bg-[#143178]  text-white   flex items-center justify-center font-bold">
                <?= strtoupper(substr(session()->get('username'), 0, 1)) ?>
            </div>
            <span class="text-sm font-medium text-[#143178] "><?= esc(session()->get('username')) ?></span>
        </button>

        <div id="userMenu" class="hidden absolute right-0 mt-2 w-40 bg-white border shadow z-50">
            <a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                Logout
            </a>
        </div>
    </div>
</header>
<div class="flex h-[calc(100vh-3.5rem)]">

<aside id="sidebar" class="sidebar-expanded bg-white   m-2   rounded-2xl  ml-2  flex flex-col  ">

<?php
$icons = [

'dashboard' => <<<SVG
<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
  <path d="M3 13h8V3H5a2 2 0 00-2 2v8zm0 6a2 2 0 002 2h6v-6H3v4zm10 2h6a2 2 0 002-2v-8h-8v10zm0-18v6h8V5a2 2 0 00-2-2h-6z"/>
</svg>
SVG,

'reports' => <<<SVG
<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
  <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6H6zm8 1.5L18.5 8H14V3.5z"/>
</svg>
SVG,

'graphs' => <<<SVG
<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
  <path d="M4 19a1 1 0 001 1h14a1 1 0 001-1V5a1 1 0 00-1-1h-2v12h-3V8h-3v8H8v-5H5v8z"/>
</svg>
SVG,

'inventory' => <<<SVG
<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
  <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
</svg>
SVG,

/* 🔥 BETTER TOPOLOGY ICON (NETWORK NODES) */
'topology' => <<<SVG
<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
  <path d="M12 2a2 2 0 012 2v3.17a3 3 0 011.94 1.94H19a2 2 0 110 4h-3.06a3 3 0 01-1.94 1.94V18a2 2 0 11-4 0v-3.06a3 3 0 01-1.94-1.94H5a2 2 0 110-4h3.06A3 3 0 0110 7.17V4a2 2 0 012-2z"/>
</svg>
SVG,

'users' => <<<SVG
<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
  <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0H5z"/>
</svg>
SVG,


'traps' => <<<SVG
<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
  <path d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0H5z"/>
</svg>
SVG,


];
?>



<?php
if (!function_exists('menu')) {
    function menu($label, $iconSvg, $url, $active = false) {

        $base = 'flex items-center gap-3 px-4 py-2.5 text-sm transition-all duration-200';

        if ($active) {
            $cls = $base . '
                bg-gradient-to-r
        from-[#0F2A5A]
        via-[#143580]
        to-[#2E5FA8]
                text-white
rounded-xl
                font-semibold';
            $iconClass = 'text-white';
        } else {
            $cls = $base . '
                text-slate-700
rounded-xl

                hover:bg-slate-300';
            $iconClass = 'text-slate-500';
        }

        echo "
        <a href='$url' class='$cls'>
            <span class='flex items-center justify-center w-5 h-5 $iconClass'>
                $iconSvg
            </span>
            <span class='menu-text'>$label</span>
        </a>";
    }
}

?>

<nav class="flex-1 px-2 py-3 space-y-1 text-sm overflow-y-auto">
<?php $modules = session()->get('modules') ?? []; ?>
<?php if (in_array('Dashboard', $modules)) menu('Dashboard',$icons['dashboard'],base_url('dashboard'),$active=='dashboard'); ?>
<?php if (in_array('Reports', $modules))   menu('Reports',$icons['reports'],base_url('reports'),$active=='reports'); ?>
<?php if (in_array('Graphs', $modules))    menu('Graphs',$icons['graphs'],base_url('graphs'),$active=='graphs'); ?>
<?php if (in_array('Inventory', $modules)) menu('Inventory',$icons['inventory'],'#'); ?>
<?php if (in_array('Topology', $modules))  menu('Topology',$icons['topology'],'#'); ?>
<?php if (in_array('Users', $modules))     menu('Users',$icons['users'],base_url('users'),$active=='users'); ?>
<?php if (in_array('Traps', $modules))     menu('Traps',$icons['traps'],base_url('traps'),$active=='traps'); ?>
</nav>

<div class="py-4 flex justify-center border-t border-slate-300">
    <button onclick="toggleSidebar()" class="text-slate-600 hover:text-slate-900">☰</button>
</div>

</aside>

<div class="flex-1 flex flex-col bg-slate-100  m-2 shadow-md   ml-1 ">
    <div class="px-6 py-2 text-lg font-bold text-slate-700 "><?= esc($title ?? '') ?></div>
    <main class="flex-1 overflow-auto p-6"><?= $this->renderSection('content') ?></main>
</div>

</div>

<script>
function toggleSidebar(){
    const sb=document.getElementById('sidebar');
    sb.classList.toggle('sidebar-collapsed');
    sb.classList.toggle('sidebar-expanded');
    sb.classList.toggle('collapsed');
}
function toggleUserMenu(){
    document.getElementById('userMenu').classList.toggle('hidden');
}
</script>

</body>
</html>
