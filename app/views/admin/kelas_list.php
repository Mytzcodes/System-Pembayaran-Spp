<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Data Kelas</h1>
            <button class="btn btn-primary" onclick="openModal('addKelasModal')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kelas
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
            <?php if (empty($kelasList)): ?>
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.3;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3>Belum Ada Data Kelas</h3>
                <p>Klik tombol "Tambah Kelas" untuk menambahkan data kelas baru</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kelas</th>
                            <th>Kompetensi Keahlian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kelasList as $kelas): ?>
                        <tr>
                            <td><?= $kelas['id'] ?></td>
                            <td><?= htmlspecialchars($kelas['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($kelas['kompetensi_keahlian']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick='editKelas(<?= json_encode($kelas) ?>)'>
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


<!-- Add Kelas Modal -->
<div id="addKelasModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Kelas</h2>
            <button class="modal-close" onclick="closeModal('addKelasModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="addKelasForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="create_kelas">
                
                <div class="form-group">
                    <label for="nama_kelas" class="form-label">Nama Kelas *</label>
                    <input type="text" id="nama_kelas" name="nama_kelas" class="form-input" required placeholder="Contoh: X RPL 1">
                </div>
                
                <div class="form-group">
                    <label for="kompetensi_keahlian" class="form-label">Kompetensi Keahlian *</label>
                    <input type="text" id="kompetensi_keahlian" name="kompetensi_keahlian" class="form-input" required placeholder="Contoh: Rekayasa Perangkat Lunak">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addKelasModal')">Batal</button>
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

<!-- Edit Kelas Modal -->
<div id="editKelasModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Kelas</h2>
            <button class="modal-close" onclick="closeModal('editKelasModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editKelasForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="update_kelas">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-group">
                    <label for="edit_nama_kelas" class="form-label">Nama Kelas *</label>
                    <input type="text" id="edit_nama_kelas" name="nama_kelas" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_kompetensi_keahlian" class="form-label">Kompetensi Keahlian *</label>
                    <input type="text" id="edit_kompetensi_keahlian" name="kompetensi_keahlian" class="form-input" required>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editKelasModal')">Batal</button>
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
// Edit Kelas Function
function editKelas(kelas) {
    document.getElementById('edit_id').value = kelas.id;
    document.getElementById('edit_nama_kelas').value = kelas.nama_kelas;
    document.getElementById('edit_kompetensi_keahlian').value = kelas.kompetensi_keahlian;
    
    openModal('editKelasModal');
}
</script>
