<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Data SPP</h1>
            <button class="btn btn-primary" onclick="openModal('addSppModal')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Tarif SPP
            </button>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Operasi berhasil dilakukan</span>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <?php if (empty($sppList)): ?>
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.3;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3>Belum Ada Data SPP</h3>
                <p>Klik tombol "Tambah Tarif SPP" untuk menambahkan tarif SPP baru</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tahun</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sppList as $spp): ?>
                        <tr>
                            <td><?= $spp['id'] ?></td>
                            <td><strong><?= $spp['tahun'] ?></strong></td>
                            <td style="color: var(--success); font-weight: 600;">Rp <?= number_format($spp['nominal']) ?></td>
                            <td><?= htmlspecialchars($spp['keterangan']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick='editSpp(<?= json_encode($spp) ?>)'>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>


<!-- Add SPP Modal -->
<div id="addSppModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Tarif SPP</h2>
            <button class="modal-close" onclick="closeModal('addSppModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="addSppForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="create_spp">
                
                <div class="form-group">
                    <label for="tahun" class="form-label">Tahun *</label>
                    <input type="number" id="tahun" name="tahun" class="form-input" required min="2020" max="2099" placeholder="Contoh: 2024">
                </div>
                
                <div class="form-group">
                    <label for="nominal" class="form-label">Nominal (Rp) *</label>
                    <input type="number" id="nominal" name="nominal" class="form-input" required min="0" step="1000" placeholder="Contoh: 500000">
                </div>
                
                <div class="form-group">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" class="form-textarea" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addSppModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit SPP Modal -->
<div id="editSppModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Tarif SPP</h2>
            <button class="modal-close" onclick="closeModal('editSppModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editSppForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="update_spp">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-group">
                    <label for="edit_tahun" class="form-label">Tahun *</label>
                    <input type="number" id="edit_tahun" name="tahun" class="form-input" required min="2020" max="2099">
                </div>
                
                <div class="form-group">
                    <label for="edit_nominal" class="form-label">Nominal (Rp) *</label>
                    <input type="number" id="edit_nominal" name="nominal" class="form-input" required min="0" step="1000">
                </div>
                
                <div class="form-group">
                    <label for="edit_keterangan" class="form-label">Keterangan</label>
                    <textarea id="edit_keterangan" name="keterangan" class="form-textarea" rows="3"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editSppModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Edit SPP Function
function editSpp(spp) {
    document.getElementById('edit_id').value = spp.id;
    document.getElementById('edit_tahun').value = spp.tahun;
    document.getElementById('edit_nominal').value = spp.nominal;
    document.getElementById('edit_keterangan').value = spp.keterangan || '';
    
    openModal('editSppModal');
}
</script>
