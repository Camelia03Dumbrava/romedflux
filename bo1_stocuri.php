<!-- Stocuri BO1 (placeholder) -->
<section x-show="tab === 'stocuri'" x-transition>
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Stocuri BO1</h2>
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full bg-white shadow rounded-lg">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Medicament</th>
                    <th class="px-4 py-2">Cantitate disponibilă</th>
                    <th class="px-4 py-2 text-right">Utilizare</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stocuri_bo1 as $row): ?>
                <tr class="border-t">
                    <td class="px-4 py-2 text-sm"><?= htmlspecialchars($row['denumire']) ?></td>
                    <td class="px-4 py-2 text-sm"><?= $row['cantitate'] ?? 0 ?></td>
                    <td class="px-4 py-2 text-sm text-right">
                        <form method="POST" class="inline">
                            <input type="hidden" name="medicament_id" value="<?= $row['id'] ?>">
                            <input type="number" name="cantitate" class="w-16 border rounded px-2 py-1 text-sm"
                                placeholder="Nr." min="1">
                            <select name="medic_id" class="text-sm border rounded px-2 py-1">
                                <option value="">Selectează medic</option>
                                <?php foreach ($medici as $medic): ?>
                                <option value="<?= $medic['id'] ?>">Dr.
                                    <?= htmlspecialchars($medic['first_name'] . ' ' . $medic['last_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="actiune" value="utilizare"
                                class="ml-2 bg-blue-600 text-white px-3 py-1 rounded text-sm">Utilizare</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Adaugă din stoc general -->
    <section class="mt-12 mb-12" id="adauga-din-general">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Adaugă din stocul general</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-6 rounded-lg shadow">
            <input type="hidden" name="actiune" value="adauga_din_general">

            <div>
                <label class="block text-sm font-medium text-gray-700">Medicament</label>
                <select name="medicament_id" required class="w-full border rounded px-3 py-2">
                    <option value="">-- Selectează --</option>
                    <?php
                    $medicamente_general = $pdo->query("SELECT m.id, m.denumire, sg.cantitate FROM medicamente m JOIN stoc_general sg ON sg.medicament_id = m.id WHERE sg.cantitate > 0")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($medicamente_general as $med) {
                        echo "<option value=\"{$med['id']}\">{$med['denumire']} ({$med['cantitate']} disponibile)</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Cantitate de mutat</label>
                <input type="number" name="cantitate" required min="1" class="w-full border rounded px-3 py-2">
            </div>

            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition w-full">
                    Mută în BO1
                </button>
            </div>
        </form>
    </section>

    <!-- Activitate stoc -->
    <section class="mt-12 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Activitate stoc</h3>
        <table class="min-w-full bg-white shadow rounded-lg">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Data</th>
                    <th class="px-4 py-2">Acțiune</th>
                    <th class="px-4 py-2">Medicament</th>
                    <th class="px-4 py-2">Cantitate</th>
                    <th class="px-4 py-2">Solicitat de</th>
                    <th class="px-4 py-2">Realizat de</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $activitati = $pdo->query("SELECT a.*, m.denumire, u.first_name AS user_first, u.last_name AS user_last, u.role AS user_role, med.first_name AS medic_first, med.last_name AS medic_last FROM activitate_stoc_bo1 a JOIN medicamente m ON m.id = a.medicament_id LEFT JOIN users u ON u.id = a.realizat_de LEFT JOIN users med ON med.id = a.solicitat_de ORDER BY a.created_at DESC")->fetchAll();
                foreach ($activitati as $a): ?>
                <tr class="border-t">
                    <td class="px-4 py-2 text-sm text-gray-600"><?= date('d.m.Y H:i', strtotime($a['created_at'])) ?></td>
                    <td class="px-4 py-2 text-sm text-gray-800"><?= ucfirst($a['actiune']) ?></td>
                    <td class="px-4 py-2 text-sm text-gray-800"><?= htmlspecialchars($a['denumire']) ?></td>
                    <td class="px-4 py-2 text-sm"><?= $a['cantitate'] ?></td>
                    <td class="px-4 py-2 text-sm">
                        <?= $a['medic_first'] ? "Dr. {$a['medic_first']} {$a['medic_last']}" : '-' ?>
                    </td>
                    <td class="px-4 py-2 text-sm">
                        <?php if ($a['user_first']): ?>
                            <span class="font-semibold text-gray-600">
                                <?= $a['user_role'] === 'administrator' ? '' : ($a['user_role'] === 'medic' ? 'Dr.' : 'As.') ?>
                            </span>
                            <?= htmlspecialchars("{$a['user_first']} {$a['user_last']}") ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($activitati)): ?>
                <tr>
                    <td colspan="6" class="px-4 py-2 text-sm text-gray-500">Nu există activități înregistrate.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</section>
