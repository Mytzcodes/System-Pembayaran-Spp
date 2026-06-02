<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>Data Siswa</h1>
            <button class="btn btn-primary" onclick="openModal('addSiswaModal')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Siswa
            </button>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>
            <?php
            switch ($_GET['success']) {
                case 'created': echo 'Siswa berhasil ditambahkan'; break;
                case 'updated': echo 'Siswa berhasil diupdate'; break;
                case 'deleted': echo 'Siswa berhasil dihapus'; break;
            }
            ?>
            </span>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <?php if (empty($siswaList)): ?>
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.3;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3>Belum Ada Data Siswa</h3>
                <p>Klik tombol "Tambah Siswa" untuk menambahkan data siswa baru</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>No. Telp</th>
                            <th>SPP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswaList as $siswa): ?>
                        <tr>
                            <td><?= htmlspecialchars($siswa['nisn']) ?></td>
                            <td><?= htmlspecialchars($siswa['nis']) ?></td>
                            <td><?= htmlspecialchars($siswa['nama']) ?></td>
                            <td><?= htmlspecialchars($siswa['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($siswa['no_telp']) ?></td>
                            <td>Rp <?= number_format($siswa['nominal']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick='editSiswa(<?= json_encode($siswa) ?>)'>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteSiswa('<?= $siswa['nisn'] ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
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

<!-- Add Siswa Modal -->
<div id="addSiswaModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Siswa</h2>
            <button class="modal-close" onclick="closeModal('addSiswaModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="addSiswaForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="create_siswa">
                
                <div class="form-group">
                    <label for="nisn" class="form-label">NISN *</label>
                    <input type="text" id="nisn" name="nisn" class="form-input" required pattern="[0-9]{10}" maxlength="10" placeholder="Masukkan NISN (10 digit)">
                </div>
                
                <div class="form-group">
                    <label for="nis" class="form-label">NIS *</label>
                    <input type="text" id="nis" name="nis" class="form-input" required maxlength="8" placeholder="Masukkan NIS">
                </div>
                
                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama" class="form-input" required placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="form-group">
                    <label for="id_kelas" class="form-label">Kelas *</label>
                    <select id="id_kelas" name="id_kelas" class="form-select" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($kelasList as $kelas): ?>
                        <option value="<?= $kelas['id'] ?>"><?= htmlspecialchars($kelas['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-textarea" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="no_telp" class="form-label">No. Telepon</label>
                    <input type="tel" id="no_telp" name="no_telp" class="form-input" placeholder="Contoh: 08123456789">
                </div>
                
                <div class="form-group">
                    <label for="id_spp" class="form-label">Tarif SPP *</label>
                    <select id="id_spp" name="id_spp" class="form-select" required>
                        <option value="">Pilih Tarif SPP</option>
                        <?php foreach ($sppList as $spp): ?>
                        <option value="<?= $spp['id'] ?>">Tahun <?= $spp['tahun'] ?> - Rp <?= number_format($spp['nominal']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addSiswaModal')">Batal</button>
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


<!-- Edit Siswa Modal -->
<div id="editSiswaModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Siswa</h2>
            <button class="modal-close" onclick="closeModal('editSiswaModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editSiswaForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="update_siswa">
                <input type="hidden" id="edit_nisn_old" name="nisn_old">
                
                <div class="form-group">
                    <label for="edit_nisn" class="form-label">NISN *</label>
                    <input type="text" id="edit_nisn" name="nisn" class="form-input" required pattern="[0-9]{10}" maxlength="10">
                </div>
                
                <div class="form-group">
                    <label for="edit_nis" class="form-label">NIS *</label>
                    <input type="text" id="edit_nis" name="nis" class="form-input" required maxlength="8">
                </div>
                
                <div class="form-group">
                    <label for="edit_nama" class="form-label">Nama Lengkap *</label>
                    <input type="text" id="edit_nama" name="nama" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_id_kelas" class="form-label">Kelas *</label>
                    <select id="edit_id_kelas" name="id_kelas" class="form-select" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($kelasList as $kelas): ?>
                        <option value="<?= $kelas['id'] ?>"><?= htmlspecialchars($kelas['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_alamat" class="form-label">Alamat</label>
                    <textarea id="edit_alamat" name="alamat" class="form-textarea" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_no_telp" class="form-label">No. Telepon</label>
                    <input type="tel" id="edit_no_telp" name="no_telp" class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="edit_id_spp" class="form-label">Tarif SPP *</label>
                    <select id="edit_id_spp" name="id_spp" class="form-select" required>
                        <option value="">Pilih Tarif SPP</option>
                        <?php foreach ($sppList as $spp): ?>
                        <option value="<?= $spp['id'] ?>">Tahun <?= $spp['tahun'] ?> - Rp <?= number_format($spp['nominal']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editSiswaModal')">Batal</button>
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

<!-- Delete Siswa Modal -->
<div id="deleteSiswaModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h2>Konfirmasi Hapus</h2>
            <button class="modal-close" onclick="closeModal('deleteSiswaModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Apakah Anda yakin ingin menghapus siswa ini? Data yang sudah dihapus tidak dapat dikembalikan.</span>
            </div>
            
            <form method="POST" id="deleteSiswaForm">
                <input type="hidden" name="csrf_token" value="<?= $auth->getCsrfToken() ?>">
                <input type="hidden" name="action" value="delete_siswa">
                <input type="hidden" id="delete_nisn" name="nisn">
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteSiswaModal')">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Edit Siswa Function
function editSiswa(siswa) {
    document.getElementById('edit_nisn_old').value = siswa.nisn;
    document.getElementById('edit_nisn').value = siswa.nisn;
    document.getElementById('edit_nis').value = siswa.nis;
    document.getElementById('edit_nama').value = siswa.nama;
    document.getElementById('edit_id_kelas').value = siswa.id_kelas;
    document.getElementById('edit_alamat').value = siswa.alamat || '';
    document.getElementById('edit_no_telp').value = siswa.no_telp || '';
    document.getElementById('edit_id_spp').value = siswa.id_spp;
    
    openModal('editSiswaModal');
}

// Delete Siswa Function
function deleteSiswa(nisn) {
    document.getElementById('delete_nisn').value = nisn;
    openModal('deleteSiswaModal');
}
</script>
