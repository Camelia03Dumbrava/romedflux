<?php
session_start();
require 'config.php';
require 'functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header("Location: login.php");
    exit;
}

// Adăugare medicament
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['action'] === 'add_med') {
    $denumire = trim($_POST['denumire']);
    $cod = trim($_POST['cod']);
    $forma = $_POST['forma_farmaceutica'];
    $concentratie = trim($_POST['concentratie']);
    $descriere = trim($_POST['descriere'] ?? '');
    $cantitate = (int) $_POST['cantitate'];

    $stmt = $pdo->prepare("SELECT id FROM medicamente WHERE cod = ?");
    $stmt->execute([$cod]);
    if ($stmt->fetch()) {
        $_SESSION['toast'] = ['message' => 'Codul acestui medicament există deja.', 'type' => 'error'];
        header("Location: admin.php");
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO medicamente (denumire, cod, forma_farmaceutica, concentratie, descriere) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$denumire, $cod, $forma, $concentratie, $descriere]);
    $medicament_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO stoc_general (medicament_id, cantitate) VALUES (?, ?)");
    $stmt->execute([$medicament_id, $cantitate]);

    $_SESSION['toast'] = ['message' => 'Medicament adăugat cu succes în stocul general.', 'type' => 'success'];
    header("Location: admin.php");
    exit;
}

// Adăugare la stocul general pentru medicament existent
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_POST['action'] === 'increase_stock') {
    $medicament_id = (int) $_POST['medicament_id'];
    $cantitate_adaugata = (int) $_POST['cantitate'];

    if ($cantitate_adaugata > 0) {
        $stmt = $pdo->prepare("UPDATE stoc_general SET cantitate = cantitate + ? WHERE medicament_id = ?");
        $stmt->execute([$cantitate_adaugata, $medicament_id]);

        $_SESSION['toast'] = ['message' => 'Cantitatea a fost adăugată la stocul general.', 'type' => 'success'];
    } else {
        $_SESSION['toast'] = ['message' => 'Cantitatea trebuie să fie mai mare decât 0.', 'type' => 'error'];
    }

    header("Location: admin.php");
    exit;
}

// Aprobare / respingere utilizatori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['user_action'])) {
    $user_id = (int)$_POST['user_id'];
    $status = $_POST['user_action'] === 'approve' ? 'active' : 'rejected';

    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->execute([$status, $user_id]);

    $_SESSION['toast'] = ['message' => $status === 'active' ? 'Utilizator aprobat cu succes.' : 'Utilizator respins.', 'type' => 'success'];
    header("Location: admin.php");
    exit;
}

// Obține utilizatorii în așteptare
$stmt = $pdo->query("SELECT id, first_name, last_name, username, role FROM users WHERE status = 'pending'");
$pending_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <title>Administrare - ROMed Flux</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="min-h-screen bg-gray-50 font-[Inter]">
    <?php include 'header.php'; ?>
    <?php
    // Preia luna și anul selectate sau luna curentă
    $luna = $_GET['luna'] ?? date('m');
    $an = $_GET['an'] ?? date('Y');

    // Pregătește interogarea SQL
    $stmt = $pdo->prepare("
    SELECT m.denumire, SUM(a.cantitate) AS total
    FROM (
        SELECT medicament_id, cantitate FROM activitate_stoc_bo1
        WHERE actiune LIKE 'Utilizare%' AND MONTH(created_at) = ? AND YEAR(created_at) = ?
        UNION ALL
        SELECT medicament_id, cantitate FROM activitate_stoc_bo2
        WHERE actiune LIKE 'Utilizare%' AND MONTH(created_at) = ? AND YEAR(created_at) = ?
    ) AS a
    JOIN medicamente m ON m.id = a.medicament_id
    GROUP BY a.medicament_id
    ORDER BY total DESC
");
    $stmt->execute([$luna, $an, $luna, $an]);
    $consum = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // pregătește date pentru JavaScript
    $labels = json_encode(array_column($consum, 'denumire'));
    $values = json_encode(array_column($consum, 'total'));
    ?>
    <main class="flex flex-col min-h-screen pt-20 space-y-12 px-4 pb-12">

        <div class="w-full max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold mb-6 mt-16">Raport utilizare - Flux medicație</h2>

            <form method="GET" class="mb-4 bg-white p-4 rounded shadow-md border flex gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Selectează luna</label>
                    <select name="luna" class="border rounded px-2 py-1" required>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $luna == $i ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Anul</label>
                    <input type="number" name="an" value="<?= $an ?>" class="border rounded px-2 py-1 w-24" required>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Vezi</button>
            </form>

            <div class="bg-white p-6 rounded shadow-md border">
                <canvas id="consumChart" height="120"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('consumChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= $labels ?>,
                    datasets: [{
                        label: 'Unități consumate',
                        data: <?= $values ?>,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantitate'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Medicament'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index'
                        }
                    }
                }
            });
        </script>

        <div class="w-full max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Utilizatori în așteptare</h2>
            <?php if (empty($pending_users)): ?>
                <p class="text-gray-600">Nu există utilizatori care așteaptă aprobare.</p>
            <?php else: ?>
                <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-4 py-2">Nume</th>
                            <th class="px-4 py-2">Utilizator</th>
                            <th class="px-4 py-2">Rol</th>
                            <th class="px-4 py-2">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_users as $user): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                </td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['role']) ?></td>
                                <td class="px-4 py-2 space-x-2">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="user_action" value="approve">
                                        <button type="submit"
                                            class="text-green-600 hover:text-green-800 font-medium">Aprobă</button>
                                    </form>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="user_action" value="reject">
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-medium">Respinge</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="w-full max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold mb-6 mt-16">Modifică cantitatea unui medicament existent</h2>
            <form method="POST" class="space-y-4 bg-white p-6 rounded-lg shadow-md border">
                <input type="hidden" name="action" value="increase_stock">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Selectează medicament</label>
                    <select name="medicament_id" required class="w-full border rounded px-3 py-2">
                        <option value="">-- Alege --</option>
                        <?php
                        $medicamente = $pdo->query("
                    SELECT m.id, m.denumire, sg.cantitate
                    FROM medicamente m
                    JOIN stoc_general sg ON sg.medicament_id = m.id
                    ORDER BY m.denumire ASC
                ")->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($medicamente as $m) {
                            echo "<option value=\"{$m['id']}\">{$m['denumire']} ({$m['cantitate']} în stoc)</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantitate de adăugat</label>
                    <input type="number" name="cantitate" required min="1" class="w-full border rounded px-3 py-2">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Actualizează stocul
                </button>
            </form>
        </div>

        <div class="w-full max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Adaugă medicament nou în stocul general</h2>
            <form method="POST" class="space-y-4 bg-white p-6 rounded-lg shadow-md border">
                <input type="hidden" name="action" value="add_med">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Denumire</label>
                    <input type="text" name="denumire" required class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cod unic (ATC)</label>
                    <input type="text" name="cod" required class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Formă farmaceutică</label>
                    <select name="forma_farmaceutica" required class="w-full border rounded px-3 py-2">
                        <option value="flacon">Flacon</option>
                        <option value="fiolă">Fiolă</option>
                        <option value="comprimat">Comprimat</option>
                        <option value="altele">Altele</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Concentrație</label>
                    <input type="text" name="concentratie" class="w-full border rounded px-3 py-2"
                        placeholder="ex: 500mg/ml">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Descriere</label>
                    <textarea name="descriere" class="w-full border rounded px-3 py-2"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantitate</label>
                    <input type="number" name="cantitate" required min="1" class="w-full border rounded px-3 py-2">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                    Adaugă medicament
                </button>
            </form>
        </div>
    </main>

    <?php include 'toast.php'; ?>
</body>

</html>