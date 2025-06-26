<?php
session_start();
require 'config.php';
require 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Salvare programare dacă există POST
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['ora'], $_POST['durata'], $_POST['procedura'], $_POST['tip'])
) {
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $durata = $_POST['durata'];
    $procedura = $_POST['procedura'];
    $chirurgie = $_POST['chirurgie'] ?? '';
    $anestezie = $_POST['anestezie'] ?? '';
    $tip = $_POST['tip'];

    if (adaugaProgramareBO2($pdo, $data, $ora, $durata, $procedura, $chirurgie, $anestezie, $tip)) {
        $_SESSION['toast'] = ['message' => 'Programarea a fost adăugată.', 'type' => 'success'];
    } else {
        $_SESSION['toast'] = ['message' => 'Eroare la salvarea programării.', 'type' => 'error'];
    }
    header("Location: bo2.php?data=" . urlencode($data));
    exit;
}

// Gestionare stoc bo2 dacă există POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['medicament_id'], $_POST['cantitate'], $_POST['actiune'])) {
    $medicament_id = (int) $_POST['medicament_id'];
    $cantitate = (int) $_POST['cantitate'];
    $actiune = $_POST['actiune'];
    $medic_id = !empty($_POST['medic_id']) ? (int) $_POST['medic_id'] : null;
    $user_id = $_SESSION['user_id'];

    if (modificaStocbo2($pdo, $actiune, $medicament_id, $cantitate, $user_id, $medic_id)) {
        $_SESSION['toast'] = ['message' => 'Modificare stoc salvată.', 'type' => 'success'];
    } else {
        $_SESSION['toast'] = ['message' => 'Eroare la modificarea stocului.', 'type' => 'error'];
    }
    header("Location: bo2.php?tab=stocuri");
    exit;
}

$can_edit = $_SESSION['role'] === 'administrator' || $_SESSION['role'] === 'medic';
$data_selectata = $_GET['data'] ?? date('Y-m-d');

$programate_stmt = $pdo->prepare("SELECT * FROM programari_bo2 WHERE data = ? AND tip = 'programata' ORDER BY ora ASC");
$programate_stmt->execute([$data_selectata]);
$programari_programate = $programate_stmt->fetchAll();

$urgente_stmt = $pdo->prepare("SELECT * FROM programari_bo2 WHERE data = ? AND tip = 'urgenta' ORDER BY ora ASC");
$urgente_stmt->execute([$data_selectata]);
$programari_urgente = $urgente_stmt->fetchAll();

$stocuri_bo2 = $pdo->query("SELECT m.id, m.denumire, sb1.cantitate FROM medicamente m JOIN stoc_bo2 sb1 ON sb1.medicament_id = m.id WHERE sb1.cantitate > 0")->fetchAll(PDO::FETCH_ASSOC);
$medici = $pdo->query("SELECT id, first_name, last_name FROM users WHERE role = 'medic' AND status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <title>BO2</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-50 font-[Inter]">
    <?php include 'header.php'; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        <?php $tab = $_GET['tab'] ?? 'program'; ?>
        <div x-data="{ tab: '<?= $tab ?>' }" class="space-y-8">
            <!-- Tab Switcher -->
            <div class="flex justify-center space-x-6 border-b">
                <button @click="tab = 'program'"
                    :class="tab === 'program' ? 'border-b-4 border-blue-600 text-blue-700' : 'text-gray-600 hover:text-blue-600'"
                    class="pb-2 text-lg font-medium">Program Operator BO2</button>
                <?php if ($_SESSION['role'] !== 'medic'): ?>
                    <button @click="tab = 'stocuri'"
                        :class="tab === 'stocuri' ? 'border-b-4 border-blue-600 text-blue-700' : 'text-gray-600 hover:text-blue-600'"
                        class="pb-2 text-lg font-medium">Stocuri BO2</button>
                <?php endif; ?>
            </div>

            <!-- Program Operator Section -->
            <div x-show="tab === 'program'">
                <?php include 'bo2_program_operator.php'; ?>
            </div>

            <!-- Stocuri bo2 -->
            <?php if ($_SESSION['role'] !== 'medic'): ?>
                <div x-show="tab === 'stocuri'">
                    <?php include 'bo2_stocuri.php'; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'toast.php'; ?>
</body>

</html>