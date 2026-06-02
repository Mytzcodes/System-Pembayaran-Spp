<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Data Petugas</h1>
            <button class="btn btn-primary" onclick="openModal('addPetugasModal')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Petugas
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
            <?php if (empty($petugasList)): ?>
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.3;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3>Belum Ada Data Petugas</h3>
                <p>Klik tombol "Tambah Petugas" untuk menambahkan data petugas baru</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Petugas</th>
                            <th>Username</th>
                            <th>No. Telp</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($petugasList as $petugas): ?>
                        <tr>
                            <td><?= $petugas['id'] ?></td>
                            <td><?= htmlspecialchars($petugas['nama_petugas']) ?></td>
                            <td><?= htmlspecialchars($petugas['username']) ?></td>
                            <td><?= htmlspecialchars($petugas['no_telp']) ?></td>
                            <td><?= htmlspecialchars($petugas['email'] ?? '-') ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick='editPetugas(<?= json_encode($petugas) ?>)'>
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


<!-- Add Petugas Modal -->
<div id="addPetugasModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Petugas</h2>
            <button class="modal-close" onclick="closeModal('addPetugasModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="addPetugasForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="create_petugas">
                
                <div class="form-group">
                    <label for="nama_petugas" class="form-label">Nama Petugas *</label>
                    <input type="text" id="nama_petugas" name="nama_petugas" class="form-input" required placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="form-group">
                    <label for="username" class="form-label">Username *</label>
                    <input type="text" id="username" name="username" class="form-input" required placeholder="Masukkan username">
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password *</label>
                    <input type="password" id="password" name="password" class="form-input" required minlength="6" placeholder="Minimal 6 karakter">
                </div>
                
                <div class="form-group">
                    <label for="no_telp" class="form-label">No. Telepon</label>
                    <input type="tel" id="no_telp" name="no_telp" class="form-input" placeholder="Contoh: 08123456789">
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="contoh@email.com">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addPetugasModal')">Batal</button>
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

<!-- Edit Petugas Modal -->
<div id="editPetugasModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Petugas</h2>
            <button class="modal-close" onclick="closeModal('editPetugasModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editPetugasForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="update_petugas">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-group">
                    <label for="edit_nama_petugas" class="form-label">Nama Petugas *</label>
                    <input type="text" id="edit_nama_petugas" name="nama_petugas" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_username" class="form-label">Username *</label>
                    <input type="text" id="edit_username" name="username" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" id="edit_password" name="password" class="form-input" minlength="6" placeholder="Minimal 6 karakter">
                </div>
                
                <div class="form-group">
                    <label for="edit_no_telp" class="form-label">No. Telepon</label>
                    <input type="tel" id="edit_no_telp" name="no_telp" class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="edit_email" class="form-label">Email</label>
                    <input type="email" id="edit_email" name="email" class="form-input">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editPetugasModal')">Batal</button>
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
// Edit Petugas Function
function editPetugas(petugas) {
    document.getElementById('edit_id').value = petugas.id;
    document.getElementById('edit_nama_petugas').value = petugas.nama_petugas;
    document.getElementById('edit_username').value = petugas.username;
    document.getElementById('edit_no_telp').value = petugas.no_telp || '';
    document.getElementById('edit_email').value = petugas.email || '';
    document.getElementById('edit_password').value = '';
    
    openModal('editPetugasModal');
}
</script>
