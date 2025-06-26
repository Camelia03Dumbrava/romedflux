<?php
session_start();
require 'config.php';
require 'functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION["role"] ?? 'rol necunoscut';
$firstName = $_SESSION["first_name"] ?? '';
$lastName = $_SESSION["last_name"] ?? '';

$fullDisplayName = trim("$firstName $lastName");

// Mesaj de bun venit o singură dată
$welcome_message = null;
if (!isset($_SESSION["welcome_shown"])) {
    $welcome_message = "Bine ai revenit, $fullDisplayName!";
    $_SESSION["welcome_shown"] = true;
}
// Obține stocurile per medicament
$query = "
    SELECT m.denumire,
           COALESCE(sg.cantitate, 0) AS general,
           COALESCE(sb1.cantitate, 0) AS bo1,
           COALESCE(sb2.cantitate, 0) AS bo2
    FROM medicamente m
    LEFT JOIN stoc_general sg ON sg.medicament_id = m.id
    LEFT JOIN stoc_bo1 sb1 ON sb1.medicament_id = m.id
    LEFT JOIN stoc_bo2 sb2 ON sb2.medicament_id = m.id
    ORDER BY m.denumire ASC
";
$stocuri = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

if ($_SESSION['role'] === 'medic') {

    // marchează notificări ca văzute
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marcheaza_vazut'])) {
        foreach ($_POST['marcheaza_vazut'] as $val) {
            [$id, $sala] = explode('_', $val);
            $tabela = ($sala === 'BO1') ? 'activitate_stoc_bo1' : 'activitate_stoc_bo2';
            $update = $pdo->prepare("UPDATE $tabela SET vazut = NOW() WHERE id = ? AND solicitat_de = ?");
            $update->execute([$id, $_SESSION['user_id']]);
        }
        header("Location: dashboard.php");
        exit;
    }

    // selectează notificările nevăzute
    $stmt = $pdo->prepare("
        SELECT a.id, a.medicament_id, a.cantitate, a.created_at, a.actiune,
               u.first_name AS as_first, u.last_name AS as_last,
               m.denumire, 'BO1' as sala
        FROM activitate_stoc_bo1 a
        JOIN users u ON u.id = a.realizat_de
        JOIN medicamente m ON m.id = a.medicament_id
        WHERE a.solicitat_de = ? AND a.actiune LIKE 'Utilizare%' AND a.vazut IS NULL

        UNION ALL

        SELECT a.id, a.medicament_id, a.cantitate, a.created_at, a.actiune,
               u.first_name AS as_first, u.last_name AS as_last,
               m.denumire, 'BO2' as sala
        FROM activitate_stoc_bo2 a
        JOIN users u ON u.id = a.realizat_de
        JOIN medicamente m ON m.id = a.medicament_id
        WHERE a.solicitat_de = ? AND a.actiune LIKE 'Utilizare%' AND a.vazut IS NULL

        ORDER BY created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
    $notificari = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ROMed Flux</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://ai-public.creatie.ai/gen_page/tailwind-custom.css" rel="stylesheet" />
    <script
        src="https://cdn.tailwindcss.com/3.4.5?plugins=forms@0.5.7,typography@0.5.13,aspect-ratio@0.4.2,container-queries@0.1.1">
    </script>
    <script src="https://ai-public.creatie.ai/gen_page/tailwind-config.min.js" data-color="#000000"
        data-border-radius="small"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="min-h-screen bg-gray-50 font-[Inter]">
    <?php include 'header.php'; ?>

    <main class="flex min-h-screen pt-16">

        <?php if ($welcome_message): ?>
            <script>
                window.addEventListener("DOMContentLoaded", () => {
                    showToast("<?= htmlspecialchars($welcome_message) ?>", "success");
                });
            </script>
        <?php endif; ?>

        <div class="flex-1 flex justify-center items-center bg-gradient-to-br from-blue-50 to-green-50"
            style="background: white;">

            <div class="w-full p-12 flex flex-col justify-center bg-transparent">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8"><a href="bo1.php"
                                class="block bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-8">
                                <div class="flex items-center"><i
                                        class="fas fa-door-open text-4xl text-blue-600 mr-4"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">BLOC OPERATOR 1</h3>
                                        <p class="text-sm text-gray-500">Interventii chirurgicale minore</p>
                                    </div>
                                </div>
                            </a><a href="bo2.php"
                                class="block bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-8">
                                <div class="flex items-center"><i
                                        class="fas fa-door-open text-4xl text-blue-600 mr-4"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">BLOC OPERATOR 2</h3>
                                        <p class="text-sm text-gray-500">Interventii chirurgicale majore</p>
                                    </div>
                                </div>
                            </a></div>
                    </div>

                    <?php if (!empty($notificari)): ?>
                        <div class="bg-white rounded-lg shadow p-6 mt-12">
                            <h2 class="text-lg font-semibold text-gray-900 mb-6">Notificări recente</h2>
                            <form method="POST">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Mesaj</th>
                                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Data</th>
                                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Acțiune</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php foreach ($notificari as $n): ?>
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-900">
                                                        As. <?= htmlspecialchars($n['as_first'] . ' ' . $n['as_last']) ?> te-a menționat în
                                                        eliberarea a <?= $n['cantitate'] ?> unități
                                                        <?= htmlspecialchars($n['denumire']) ?> în <?= $n['sala'] ?>
                                                    </td>
                                                    <td class="px-4 py-2 text-center text-sm text-gray-700">
                                                        <?= date('d.m.Y H:i', strtotime($n['created_at'])) ?>
                                                    </td>
                                                    <td class="px-4 py-2 text-center text-sm">
                                                        <button name="marcheaza_vazut[]" value="<?= $n['id'] . '_' . $n['sala'] ?>"
                                                            class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700 transition">
                                                            Văzut
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <h1 class="text-2xl font-bold text-gray-900 mb-6 mt-6">Stocuri</h1>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-900">Stoc Curent</h2>
                        <?php if (empty($stocuri)): ?>
                            <p class="text-gray-600">Nu există medicamente în stoc.</p>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Medicament
                                            </th>
                                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">General
                                            </th>
                                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">BO1</th>
                                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">BO2</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($stocuri as $stoc): ?>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">
                                                    <?= htmlspecialchars($stoc['denumire']) ?></td>
                                                <td
                                                    class="px-4 py-2 text-center text-sm <?= $stoc['general'] <= 3 ? 'text-red-600 font-semibold' : 'text-gray-700' ?>">
                                                    <?= $stoc['general'] ?>
                                                    <?php if ($stoc['general'] <= 3): ?>
                                                        <i class="fas fa-triangle-exclamation ml-1"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td
                                                    class="px-4 py-2 text-center text-sm <?= $stoc['bo1'] <= 1 ? 'text-yellow-600 font-semibold' : 'text-gray-700' ?>">
                                                    <?= $stoc['bo1'] ?>
                                                    <?php if ($stoc['bo1'] <= 1): ?>
                                                        <i class="fas fa-triangle-exclamation ml-1"></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td
                                                    class="px-4 py-2 text-center text-sm <?= $stoc['bo2'] <= 1 ? 'text-yellow-600 font-semibold' : 'text-gray-700' ?>">
                                                    <?= $stoc['bo2'] ?>
                                                    <?php if ($stoc['bo2'] <= 1): ?>
                                                        <i class="fas fa-triangle-exclamation ml-1"></i>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center py-8">
                    <div class="flex space-x-6 mb-4"><a href="#"
                            class="text-sm text-gray-600 hover:text-gray-900">Politica de Confidențialitate</a><a
                            href="#" class="text-sm text-gray-600 hover:text-gray-900">Termeni și Condiții</a><a
                            href="#" class="text-sm text-gray-600 hover:text-gray-900">Suport</a></div>
                    <p class="text-sm text-gray-500">© 2025 ROMed Flux. Toate drepturile rezervate.</p>
                </div>
            </div>
        </div>
    </footer>
    <?php include 'toast.php'; ?>
</body>

</html>