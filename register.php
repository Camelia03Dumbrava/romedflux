<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $role = trim($_POST["role"]);

    if (empty($username) || empty($password) || empty($confirm_password) || empty($first_name) || empty($last_name)) {
        $error = "Toate câmpurile sunt obligatorii!";
    } elseif ($password !== $confirm_password) {
        $error = "Parolele nu coincid!";
    } else {
        // Verificăm dacă utilizatorul există deja
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = "Numele de utilizator este deja folosit!";
        } else {
            // Hash-uim parola și salvăm utilizatorul
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, password, role, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            if ($stmt->execute([$first_name, $last_name, $username, $hashed_password, $role])) {
                //session_start();
                require 'functions.php'; // dacă ai definit acolo `setToast()`

                setToast("Cont creat cu succes. Acum vă puteți autentifica.", "success");

                header("Location: login.php?success=2");
                exit;
            } else {
                $error = "Eroare la crearea contului!";
            }
        }
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
    <title>ROMed Flux - Înregistrare</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://ai-public.creatie.ai/gen_page/tailwind-custom.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com/3.4.5"></script>
</head>

<body class="min-h-screen bg-gray-50 font-[Inter]">

    <header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="https://ai-public.creatie.ai/gen_page/logo_placeholder.png" alt="ROMed Flux Logo"
                        class="h-12 w-auto" />
                    <span class="ml-3 text-xl font-semibold text-gray-900">ROMed Flux</span>
                </div>
            </div>
        </div>
    </header>

    <main class="flex min-h-screen pt-16">
        <div class="flex-1 flex justify-center items-center bg-gradient-to-br from-blue-50 to-green-50"
            style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1571772996211-2f02c9727629?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
            <div class="w-full p-12 flex flex-col justify-center bg-transparent">
                <div class="max-w-md mx-auto w-full bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Înregistrare</h2>

                    <?php if (isset($toast_script)) echo $toast_script; ?>

                    <form method="POST" action="register.php" class="space-y-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Prenume</label>
                            <div class="mt-1 relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" id="first_name" name="first_name" required
                                    class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Introduceți prenumele" />
                            </div>
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Nume</label>
                            <div class="mt-1 relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" id="last_name" name="last_name" required
                                    class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Introduceți numele" />
                            </div>
                        </div>
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Nume
                                utilizator</label>
                            <div class="mt-1 relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" id="username" name="username" required
                                    class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Introduceți numele de utilizator" />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Parolă</label>
                            <div class="mt-1 relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" id="password" name="password" required
                                    class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Introduceți parola" />
                            </div>
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirmare
                                Parolă</label>
                            <div class="mt-1 relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" id="confirm_password" name="confirm_password" required
                                    class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Reintroduceți parola" />
                            </div>
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                            <select id="role" name="role" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="asistent">Asistent</option>
                                <option value="medic">Medic</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Înregistrare</button>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-600">Ai deja cont? <a href="login.php"
                            class="text-blue-600 hover:text-blue-700 font-medium">Autentificare</a></p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center py-8">
                <p class="text-sm text-gray-500">© 2025 ROMed Flux. Toate drepturile rezervate.</p>
            </div>
        </div>
    </footer>
    <?php include 'toast.php'; ?>
</body>

</html>