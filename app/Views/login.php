<!DOCTYPE html>
<html>
<head>
    <title>LNMS Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-400 to-blue-400 h-screen flex items-center justify-center">

<form method="post" action="<?= base_url('login') ?>"  class="bg-white p-8 rounded-lg shadow w-96">
    <h2 class="text-2xl font-bold text-center mb-6">LNMS Login</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <p class="text-red-500 text-center mb-4">
            <?= session()->getFlashdata('error') ?>
        </p>
    <?php endif; ?>

    <input name="username" placeholder="Username"
           class="border w-full p-2 mb-4 rounded" required>

    <input name="password" type="password" placeholder="Password"
           class="border w-full p-2 mb-6 rounded" required>

    <button class="bg-green-600 hover:bg-green-700 text-white w-full py-2 rounded">
        Login
    </button>
</form>

</body>
</html>
