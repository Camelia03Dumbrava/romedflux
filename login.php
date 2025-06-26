<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$success_message = null;

// Verifică dacă există toast în sesiune
if (isset($_SESSION['toast'])) {
    $success_message = $_SESSION['toast']['message'];
    unset($_SESSION['toast']); // ștergem mesajul imediat după afișare
}

$success_message = null;

if (isset($_GET['success'])) {
    if ($_GET['success'] == '1') {
        $success_message = "Ați fost delogat cu succes.";
    } elseif ($_GET['success'] == '2') {
        $success_message = "Contul dvs. este în așteptare. Așteptați aprobarea unui administrator.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, username, password, role, first_name, last_name, status FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            if ($user["status"] !== 'active') {
                if ($user["status"] === 'pending') {
                    $error = "Contul dvs. este în așteptare. Așteptați aprobarea unui administrator.";
                } elseif ($user["status"] === 'rejected') {
                    $error = "Contul dvs. a fost respins.";
                } else {
                    $error = "Status necunoscut. Contactați administratorul.";
                }
            } elseif (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["first_name"] = $user["first_name"];
                $_SESSION["last_name"] = $user["last_name"];
                $_SESSION["role"] = $user["role"];
    
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Utilizator sau parolă incorectă!";
            }
        } else {
            $error = "Utilizatorul nu există.";
        }
    } else {
        $error = "Toate câmpurile sunt obligatorii!";
    }
}

$toast_script = null;
if (isset($error)) {
    $toast_script = "<script>
        window.addEventListener('DOMContentLoaded', () => {
            showToast(" . json_encode($error) . ", 'error');
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ROMed Flux - Autentificare</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://ai-public.creatie.ai/gen_page/tailwind-custom.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com/3.4.5"></script>
</head>

<body class="min-h-screen bg-gray-50 font-[Inter]">

    <header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="https://ai-public.creatie.ai/gen_page/logo_placeholder.png" alt="ROMed Flux Logo" class="h-12 w-auto" />
                    <span class="ml-3 text-xl font-semibold text-gray-900">ROMed Flux</span>
                </div>
            </div>
        </div>
    </header>

    <main class="flex min-h-screen pt-16">
        <div class="flex-1 flex justify-center items-center bg-gradient-to-br from-blue-50 to-green-50" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1571772996211-2f02c9727629?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
            <div class="w-full p-12 flex flex-col justify-center bg-transparent">
                <div class="max-w-md mx-auto w-full bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Autentificare</h2>

                    <?php if (isset($success_message)): ?>
                        <script>
                            window.addEventListener("DOMContentLoaded", () => {
                                showToast("<?= htmlspecialchars($success_message) ?>", "success");
                            });
                        </script>
                    <?php endif; ?>
                    <?php if (isset($toast_script)) echo $toast_script; ?>

                    <form method="POST" action="login.php" class="space-y-6">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Nume utilizator</label>
                            <div class="mt-1 relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" id="username" name="username" required class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Introduceți numele de utilizator" />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Parolă</label>
                            <div class="mt-1 relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" id="password" name="password" required class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Introduceți parola" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember-me" name="remember-me" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-700">Ține-mă minte</label>
                            </div>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700">Ați uitat parola?</a>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Conectare</button>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-600">Nu aveți cont? <a href="register.php" class="text-blue-600 hover:text-blue-700 font-medium">Înregistrați-vă</a></p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center py-8">
                <div class="flex space-x-6 mb-4">
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900">Politica de Confidențialitate</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900">Termeni și Condiții</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-gray-900">Suport</a>
                </div>
                <p class="text-sm text-gray-500">© 2024 ROMed Flux. Toate drepturile rezervate.</p>
            </div>
        </div>
    </footer>
    <?php include 'toast.php'; ?>
</body>

</html>