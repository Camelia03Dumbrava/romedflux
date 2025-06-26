<?php

function setToast(string $message, string $type = 'success'): void {
    $_SESSION['toast'] = [
        'message' => $message,
        'type' => $type
    ];
}

function getDisplayName(array $user): string {
    $role = $user['role'] ?? '';
    $firstName = $user['first_name'] ?? '';
    $lastName = $user['last_name'] ?? '';

    if ($role === 'administrator') {
        return trim("As. Sef $firstName $lastName");
    }

    $prefix = $role === 'medic' ? 'Dr.' : 'As.';
    return trim("$prefix $firstName $lastName");
}

function adaugaProgramare($pdo, $data, $ora, $durata, $procedura, $chirurgie, $anestezie, $tip) {
    $stmt = $pdo->prepare("
        INSERT INTO programari_bo1 (data, ora, durata, procedura, chirurgie, anestezie, tip)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([$data, $ora, $durata, $procedura, $chirurgie, $anestezie, $tip]);
}

function modificaStocBO1(PDO $pdo, string $actiune, int $medicament_id, int $cantitate, int $user_id, ?int $medic_id = null): bool {
    if ($cantitate <= 0) return false;

    try {
        $pdo->beginTransaction();

        // Obține cantitățile curente
        $getStocBO = $pdo->prepare("SELECT cantitate FROM stoc_bo1 WHERE medicament_id = ?");
        $getStocBO->execute([$medicament_id]);
        $bo1Row = $getStocBO->fetch(PDO::FETCH_ASSOC);
        $stoc_bo1 = $bo1Row ? (int)$bo1Row['cantitate'] : 0;

        $getStocGeneral = $pdo->prepare("SELECT cantitate FROM stoc_general WHERE medicament_id = ?");
        $getStocGeneral->execute([$medicament_id]);
        $genRow = $getStocGeneral->fetch(PDO::FETCH_ASSOC);
        $stoc_general = $genRow ? (int)$genRow['cantitate'] : 0;

        if ($actiune === 'adauga_din_general') {
            if ($cantitate > $stoc_general) {
                $pdo->rollBack();
                return false;
            }

            // Mută din general în BO1
            $pdo->prepare("UPDATE stoc_general SET cantitate = cantitate - ? WHERE medicament_id = ?")
                ->execute([$cantitate, $medicament_id]);

            $pdo->prepare("INSERT INTO stoc_bo1 (medicament_id, cantitate) VALUES (?, ?) ON DUPLICATE KEY UPDATE cantitate = cantitate + VALUES(cantitate)")
                ->execute([$medicament_id, $cantitate]);

            $act = "Mutare din stoc general în BO1";
        } elseif ($actiune === 'utilizare') {
            if ($cantitate > $stoc_bo1) {
                $pdo->rollBack();
                return false;
            }

            // Scade din BO1
            $pdo->prepare("UPDATE stoc_bo1 SET cantitate = cantitate - ? WHERE medicament_id = ?")
                ->execute([$cantitate, $medicament_id]);

            $act = "Utilizare medicament în BO1";
        } else {
            $pdo->rollBack();
            return false;
        }

        // Înregistrează activitatea
        $pdo->prepare("INSERT INTO activitate_stoc_bo1 (medicament_id, cantitate, actiune, realizat_de, solicitat_de, created_at)
               VALUES (?, ?, ?, ?, ?, NOW())")
    ->execute([$medicament_id, $cantitate, $act, $user_id, $medic_id]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ' - Eroare DB: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        $pdo->rollBack();
        return false;
    }
}
function adaugaProgramareBO2($pdo, $data, $ora, $durata, $procedura, $chirurgie, $anestezie, $tip) {
    $stmt = $pdo->prepare("INSERT INTO programari_bo2 (data, ora, durata, procedura, chirurgie, anestezie, tip) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$data, $ora, $durata, $procedura, $chirurgie, $anestezie, $tip]);
}

function modificaStocBO2(PDO $pdo, string $actiune, int $medicament_id, int $cantitate, int $user_id, ?int $medic_id = null): bool {
    if ($cantitate <= 0) return false;

    try {
        $pdo->beginTransaction();

        // Obține cantități existente
        $getStocBO = $pdo->prepare("SELECT cantitate FROM stoc_bo2 WHERE medicament_id = ?");
        $getStocBO->execute([$medicament_id]);
        $bo2Row = $getStocBO->fetch(PDO::FETCH_ASSOC);
        $stoc_bo2 = $bo2Row ? (int)$bo2Row['cantitate'] : 0;

        $getStocGeneral = $pdo->prepare("SELECT cantitate FROM stoc_general WHERE medicament_id = ?");
        $getStocGeneral->execute([$medicament_id]);
        $genRow = $getStocGeneral->fetch(PDO::FETCH_ASSOC);
        $stoc_general = $genRow ? (int)$genRow['cantitate'] : 0;

        if ($actiune === 'adauga_din_general') {
            if ($cantitate > $stoc_general) {
                $pdo->rollBack();
                return false;
            }

            $pdo->prepare("UPDATE stoc_general SET cantitate = cantitate - ? WHERE medicament_id = ?")
                ->execute([$cantitate, $medicament_id]);

            $pdo->prepare("INSERT INTO stoc_bo2 (medicament_id, cantitate) VALUES (?, ?) ON DUPLICATE KEY UPDATE cantitate = cantitate + VALUES(cantitate)")
                ->execute([$medicament_id, $cantitate]);

            $act = "Mutare din stoc general în BO2";
        } elseif ($actiune === 'utilizare') {
            if ($cantitate > $stoc_bo2) {
                $pdo->rollBack();
                return false;
            }

            $pdo->prepare("UPDATE stoc_bo2 SET cantitate = cantitate - ? WHERE medicament_id = ?")
                ->execute([$cantitate, $medicament_id]);

            $act = "Utilizare medicament în BO2";
        } else {
            $pdo->rollBack();
            return false;
        }

        // Înregistrare activitate
        $pdo->prepare("
            INSERT INTO activitate_stoc_bo2 (medicament_id, cantitate, actiune, realizat_de, solicitat_de, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ")->execute([$medicament_id, $cantitate, $act, $user_id, $medic_id]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}
?>