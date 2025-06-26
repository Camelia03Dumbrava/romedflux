 <!-- Program Operator Section -->
 <section x-show="tab === 'program'" x-transition>
     <div class="text-center mb-6">
         <h1 class="text-2xl font-bold text-gray-800 mb-2">Program Operator - BO1</h1>
         <form method="GET" class="inline-block">
             <label class="mr-2 text-sm text-gray-600">Selectează data:</label>
             <input type="date" name="data" value="<?= htmlspecialchars($data_selectata) ?>"
                 class="border px-3 py-1 rounded" onchange="this.form.submit()">
         </form>
     </div>

     <!-- Intervenții programate -->
     <h2 class="text-xl font-semibold text-gray-800 mb-2">Intervenții programate</h2>
     <div class="overflow-x-auto mb-6">
         <table class="min-w-full bg-white shadow rounded-lg">
             <thead class="bg-gray-100 text-left">
                 <tr>
                     <th class="px-4 py-2">Ora</th>
                     <th class="px-4 py-2">Procedura</th>
                     <th class="px-4 py-2">Durata</th>
                     <th class="px-4 py-2">Chirurgie</th>
                     <th class="px-4 py-2">Anestezie</th>
                 </tr>
             </thead>
             <tbody>
                 <?php foreach ($programari_programate as $p): ?>
                     <!-- Afișează programarea reală -->
                     <tr class="border-t">
                         <td class="px-4 py-2 text-sm"><?= htmlspecialchars(substr($p['ora'], 0, 5)) ?></td>
                         <td class="px-4 py-2 text-sm text-red-800 font-medium"><?= htmlspecialchars($p['procedura']) ?>
                         </td>
                         <td class="px-4 py-2 text-sm text-red-700 font-medium"><?= htmlspecialchars($p['durata']) ?></td>
                         <td class="px-4 py-2 text-sm"><?= htmlspecialchars($p['chirurgie']) ?></td>
                         <td class="px-4 py-2 text-sm"><?= htmlspecialchars($p['anestezie']) ?></td>
                     </tr>

                     <!-- Afișează procedura de sterilizare -->
                     <tr class="border-t">
                         <td class="px-4 py-2 text-sm text-gray-400">-</td>
                         <td class="px-4 py-2 text-sm font-medium text-green-600">STERILIZARE</td>
                         <td class="px-4 py-2 text-sm text-green-600">10min</td>
                         <td class="px-4 py-2 text-sm">-</td>
                         <td class="px-4 py-2 text-sm">-</td>
                     </tr>
                 <?php endforeach; ?>
                 <?php if (empty($programari_programate)): ?>
                     <tr>
                         <td colspan="5" class="px-4 py-2 text-sm text-gray-500">Nu sunt programări.</td>
                     </tr>
                 <?php endif; ?>
             </tbody>
         </table>
     </div>

     <!-- Urgente -->
     <h2 class="text-xl font-semibold text-red-700 mb-2">Urgențe</h2>
     <div class="overflow-x-auto">
         <table class="min-w-full bg-white shadow rounded-lg">
             <thead class="bg-red-100 text-left">
                 <tr>
                     <th class="px-4 py-2">Ora</th>
                     <th class="px-4 py-2">Procedura</th>
                     <th class="px-4 py-2">Durata</th>
                     <th class="px-4 py-2">Chirurgie</th>
                     <th class="px-4 py-2">Anestezie</th>
                 </tr>
             </thead>
             <tbody>
                 <?php foreach ($programari_urgente as $p): ?>
                     <tr class="border-t">
                         <td class="px-4 py-2 text-sm"><?= htmlspecialchars(substr($p['ora'], 0, 5)) ?></td>
                         <td class="px-4 py-2 text-sm text-red-800 font-medium"><?= htmlspecialchars($p['procedura']) ?>
                         </td>
                         <td class="px-4 py-2 text-sm text-red-600 font-medium"><?= htmlspecialchars($p['durata']) ?></td>
                         <td class="px-4 py-2 text-sm"><?= htmlspecialchars($p['chirurgie']) ?></td>
                         <td class="px-4 py-2 text-sm"><?= htmlspecialchars($p['anestezie']) ?></td>
                     </tr>
                     <tr class="border-t">
                         <td class="px-4 py-2 text-sm text-gray-400">-</td>
                         <td class="px-4 py-2 text-sm font-medium text-green-600">STERILIZARE</td>
                         <td class="px-4 py-2 text-sm text-green-600">10min</td>
                         <td class="px-4 py-2 text-sm">-</td>
                         <td class="px-4 py-2 text-sm">-</td>
                     </tr>
                 <?php endforeach; ?>
                 <?php if (empty($programari_urgente)): ?>
                     <tr>
                         <td colspan="5" class="px-4 py-2 text-sm text-gray-500">Nu sunt urgențe.</td>
                     </tr>
                 <?php endif; ?>
             </tbody>
         </table>
     </div>

     <?php if ($can_edit): ?>
         <?php $medici_activi = $pdo->query("SELECT id, first_name, last_name FROM users WHERE role = 'medic' AND status = 'active'")->fetchAll(PDO::FETCH_ASSOC); ?>
         <!-- Formular adaugare programare -->
         <section class="bg-white p-6 rounded-lg shadow mt-8">
             <h2 class="text-lg font-semibold mb-4">Adaugă intervenție</h2>
             <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                 <input type="hidden" name="data" value="<?= htmlspecialchars($data_selectata) ?>">
                 <div>
                     <label class="block text-sm font-medium">Ora</label>
                     <input type="time" name="ora" required class="w-full border rounded px-3 py-2">
                 </div>
                 <div>
                     <label class="block text-sm font-medium">Durata</label>
                     <input type="text" name="durata" required placeholder="ex: 1h, 45min"
                         class="w-full border rounded px-3 py-2">
                 </div>
                 <div class="md:col-span-2">
                     <label class="block text-sm font-medium">Procedură</label>
                     <input type="text" name="procedura" required class="w-full border rounded px-3 py-2">
                 </div>
                 <div>
                     <label class="block text-sm font-medium">Chirurgie</label>
                     <select name="chirurgie" class="w-full border rounded px-3 py-2">
                         <option value="">-- Selectează medic --</option>
                         <?php foreach ($medici_activi as $medic): ?>
                             <option value="<?= htmlspecialchars("Dr. {$medic['first_name']} {$medic['last_name']}") ?>">
                                 <?= htmlspecialchars("Dr. {$medic['first_name']} {$medic['last_name']}") ?>
                             </option>
                         <?php endforeach; ?>
                     </select>
                 </div>

                 <div>
                     <label class="block text-sm font-medium">Anestezie</label>
                     <select name="anestezie" class="w-full border rounded px-3 py-2">
                         <option value="">-- Selectează medic --</option>
                         <?php foreach ($medici_activi as $medic): ?>
                             <option value="<?= htmlspecialchars("Dr. {$medic['first_name']} {$medic['last_name']}") ?>">
                                 <?= htmlspecialchars("Dr. {$medic['first_name']} {$medic['last_name']}") ?>
                             </option>
                         <?php endforeach; ?>
                     </select>
                 </div>
                 <div>
                     <label class="block text-sm font-medium">Tip</label>
                     <select name="tip" class="w-full border rounded px-3 py-2">
                         <option value="programata">Programată</option>
                         <option value="urgenta">Urgență</option>
                     </select>
                 </div>
                 <div class="md:col-span-2">
                     <?php
                        $azi = new DateTime();
                        $data_selectata_obj = new DateTime($data_selectata ?? date('Y-m-d'));
                        $interval = $azi->diff($data_selectata_obj);
                        $disable_adauga = $data_selectata_obj < $azi && $interval->days > 1;
                        ?>
                     <button type="submit"
                         class="bg-blue-600 text-white px-4 py-2 rounded <?= $disable_adauga ? 'opacity-50 cursor-not-allowed' : '' ?>"
                         <?= $disable_adauga ? 'disabled' : '' ?>>
                         Adaugă
                     </button>
                     <?php if ($disable_adauga): ?>
                         <p class="text-sm text-gray-500 mt-2">
                             Nu se pot adăuga intervenții dacă au trecut 24h.
                         </p>
                     <?php endif; ?>
                 </div>
             </form>
         </section>
     <?php endif; ?>
 </section>