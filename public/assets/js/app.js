// ============================================
// MODERN SPP SYSTEM - COMPLETE JAVASCRIPT
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    initThemeToggle();
    initUserMenu();
    initFormValidation();
    initAutocomplete();
    initModals();
    initAlerts();
});

// ============================================
// SIDEBAR TOGGLE
// ============================================

function initSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            const isExpanded = !sidebar.classList.contains('collapsed');
            sidebarToggle.setAttribute('aria-expanded', isExpanded);
            
            // Save state
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
        
        // Restore state
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            sidebarToggle.setAttribute('aria-expanded', 'false');
        }
    }
}

// ============================================
// DARK MODE TOGGLE
// ============================================

function initThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    if (currentTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
    
    if (themeToggle) {
        updateThemeIcon(currentTheme);
        
        themeToggle.addEventListener('click', function() {
            const theme = document.documentElement.getAttribute('data-theme');
            const newTheme = theme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
            
            Toast.info(`Mode ${newTheme === 'dark' ? 'Gelap' : 'Terang'} diaktifkan`);
        });
    }
}

function updateThemeIcon(theme) {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;
    
    const sunIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>`;
    
    const moonIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>`;
    
    themeToggle.innerHTML = theme === 'dark' ? sunIcon : moonIcon;
}

// ============================================
// USER MENU DROPDOWN
// ============================================

function initUserMenu() {
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userMenuDropdown = document.getElementById('userMenuDropdown');
    
    if (userMenuBtn && userMenuDropdown) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenuDropdown.classList.toggle('active');
        });
        
        document.addEventListener('click', function() {
            userMenuDropdown.classList.remove('active');
        });
    }
}

// ============================================
// FORM VALIDATION
// ============================================

function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    field.style.borderColor = 'var(--danger)';
                    field.style.animation = 'shake 0.5s';
                    
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                    
                    // Add error message
                    let errorMsg = field.parentElement.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message';
                        errorMsg.style.cssText = 'color: var(--danger); font-size: 0.75rem; margin-top: 0.25rem;';
                        errorMsg.textContent = 'Field ini wajib diisi';
                        field.parentElement.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('error');
                    field.style.borderColor = '';
                    const errorMsg = field.parentElement.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                Toast.error('Mohon lengkapi semua field yang wajib diisi');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Remove error on input
        form.querySelectorAll('[required]').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('error');
                this.style.borderColor = '';
                const errorMsg = this.parentElement.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });
        });
    });
    
    // Add shake animation
    if (!document.getElementById('shake-animation')) {
        const style = document.createElement('style');
        style.id = 'shake-animation';
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);
    }
}

// ============================================
// AUTOCOMPLETE SEARCH
// ============================================

function initAutocomplete() {
    const nisnSearch = document.getElementById('nisn_search');
    const searchResults = document.getElementById('searchResults');
    
    if (nisnSearch && searchResults) {
        let searchTimeout;
        
        nisnSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.classList.remove('active');
                return;
            }
            
            searchTimeout = setTimeout(() => {
                // Simulate search (replace with actual API call)
                searchSiswa(query);
            }, 300);
        });
        
        document.addEventListener('click', function(e) {
            if (!nisnSearch.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.remove('active');
            }
        });
    }
}

function searchSiswa(query) {
    const searchResults = document.getElementById('searchResults');
    
    // Get base URL from current location
    const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -2).join('/');
    
    fetch(`${baseUrl}/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                searchResults.innerHTML = data.map(siswa => 
                    `<div class="autocomplete-item" onclick='selectSiswa(${JSON.stringify(siswa)})'>
                        <strong>${siswa.nisn}</strong> - ${siswa.nama} <span style="color: var(--text-secondary);">(${siswa.nama_kelas})</span>
                    </div>`
                ).join('');
                searchResults.classList.add('active');
            } else {
                searchResults.innerHTML = '<div class="autocomplete-item" style="color: var(--text-secondary);">Tidak ada hasil ditemukan</div>';
                searchResults.classList.add('active');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            searchResults.innerHTML = '<div class="autocomplete-item" style="color: var(--danger);">Terjadi kesalahan saat mencari</div>';
            searchResults.classList.add('active');
        });
}

function selectSiswa(siswa) {
    document.getElementById('nisn').value = siswa.nisn;
    document.getElementById('id_spp').value = siswa.id_spp;
    document.getElementById('jumlah_bayar').value = siswa.nominal;
    
    document.getElementById('info_nisn').textContent = siswa.nisn;
    document.getElementById('info_nama').textContent = siswa.nama;
    document.getElementById('info_kelas').textContent = siswa.nama_kelas;
    document.getElementById('info_spp').textContent = 'Rp ' + parseInt(siswa.nominal).toLocaleString('id-ID');
    
    document.getElementById('siswaInfo').style.display = 'block';
    document.getElementById('searchResults').classList.remove('active');
    document.getElementById('nisn_search').value = siswa.nisn + ' - ' + siswa.nama;
}

// ============================================
// MODAL FUNCTIONS
// ============================================

function initModals() {
    // Close modal on backdrop click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.active').forEach(modal => {
                closeModal(modal.id);
            });
        }
    });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Focus first input
        const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            // Remove error messages
            form.querySelectorAll('.error-message').forEach(msg => msg.remove());
            form.querySelectorAll('.error').forEach(field => {
                field.classList.remove('error');
                field.style.borderColor = '';
            });
        }
    }
}

// ============================================
// CRUD FUNCTIONS
// ============================================

function editSiswa(siswa) {
    // Populate edit modal
    const modal = document.getElementById('editSiswaModal') || document.getElementById('addSiswaModal');
    if (!modal) return;
    
    const form = modal.querySelector('form');
    if (!form) return;
    
    // Change form action
    form.querySelector('[name="action"]').value = 'update_siswa';
    
    // Populate fields
    form.querySelector('[name="nisn"]').value = siswa.nisn;
    form.querySelector('[name="nisn"]').readOnly = true;
    form.querySelector('[name="nis"]').value = siswa.nis;
    form.querySelector('[name="nama"]').value = siswa.nama;
    form.querySelector('[name="id_kelas"]').value = siswa.id_kelas;
    form.querySelector('[name="alamat"]').value = siswa.alamat || '';
    form.querySelector('[name="no_telp"]').value = siswa.no_telp || '';
    form.querySelector('[name="id_spp"]').value = siswa.id_spp;
    
    // Change modal title
    const title = modal.querySelector('h2');
    if (title) title.textContent = 'Edit Siswa';
    
    openModal(modal.id);
}

function deleteSiswa(nisn, nama) {
    Confirm(
        `Apakah Anda yakin ingin menghapus siswa <strong>${nama}</strong>?<br><small>Data yang sudah dihapus tidak dapat dikembalikan.</small>`,
        function() {
            Loading.show('Menghapus data...');
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="csrf_token" value="${getCsrfToken()}">
                <input type="hidden" name="action" value="delete_siswa">
                <input type="hidden" name="nisn" value="${nisn}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    );
}

function editPetugas(petugas) {
    console.log('Edit petugas:', petugas);
    Toast.info('Fitur edit petugas akan segera tersedia');
}

function editKelas(kelas) {
    console.log('Edit kelas:', kelas);
    Toast.info('Fitur edit kelas akan segera tersedia');
}

function editSpp(spp) {
    console.log('Edit SPP:', spp);
    Toast.info('Fitur edit SPP akan segera tersedia');
}

function viewReceipt(paymentId) {
    const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/');
    window.location.href = `${baseUrl}/receipt/${paymentId}`;
}

// ============================================
// PRINT RECEIPT
// ============================================

function printReceipt() {
    window.print();
}

// ============================================
// TOAST NOTIFICATION SYSTEM
// ============================================

const Toast = {
    container: null,
    
    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },
    
    show(message, type = 'info', duration = 5000) {
        this.init();
        
        const icons = {
            success: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #10B981;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #EF4444;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #F59E0B;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>`,
            info: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #3B82F6;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>`
        };
        
        const titles = {
            success: 'Berhasil',
            error: 'Error',
            warning: 'Peringatan',
            info: 'Informasi'
        };
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-content">
                <div class="toast-title">${titles[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;
        
        this.container.appendChild(toast);
        
        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    },
    
    success(message, duration = 5000) {
        this.show(message, 'success', duration);
    },
    
    error(message, duration = 7000) {
        this.show(message, 'error', duration);
    },
    
    warning(message, duration = 6000) {
        this.show(message, 'warning', duration);
    },
    
    info(message, duration = 5000) {
        this.show(message, 'info', duration);
    }
};

// ============================================
// LOADING OVERLAY
// ============================================

const Loading = {
    overlay: null,
    
    show(message = 'Memuat...') {
        if (!this.overlay) {
            this.overlay = document.createElement('div');
            this.overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                backdrop-filter: blur(4px);
            `;
            this.overlay.innerHTML = `
                <div style="background: var(--surface); padding: 2rem; border-radius: var(--radius-xl); text-align: center; box-shadow: var(--shadow-xl);">
                    <div style="width: 48px; height: 48px; border: 4px solid var(--gray-200); border-top-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                    <div style="font-size: 1rem; color: var(--text); font-weight: 500;">${message}</div>
                </div>
            `;
            document.body.appendChild(this.overlay);
            document.body.style.overflow = 'hidden';
            
            // Add spin animation if not exists
            if (!document.getElementById('spin-animation')) {
                const style = document.createElement('style');
                style.id = 'spin-animation';
                style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
                document.head.appendChild(style);
            }
        }
    },
    
    hide() {
        if (this.overlay) {
            this.overlay.style.opacity = '0';
            setTimeout(() => {
                this.overlay.remove();
                this.overlay = null;
                document.body.style.overflow = '';
            }, 200);
        }
    }
};

// ============================================
// CONFIRMATION DIALOG
// ============================================

function Confirm(message, onConfirm, onCancel) {
    const dialog = document.createElement('div');
    dialog.className = 'modal active';
    dialog.style.zIndex = '10000';
    
    dialog.innerHTML = `
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h2>Konfirmasi</h2>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 64px; height: 64px; color: var(--warning); margin: 0 auto;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p style="text-align: center; margin-bottom: 1.5rem; color: var(--text);">${message}</p>
                <div style="display: flex; gap: 0.75rem; justify-content: center;">
                    <button id="confirmYes" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Ya, Hapus
                    </button>
                    <button id="confirmNo" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(dialog);
    document.body.style.overflow = 'hidden';
    
    document.getElementById('confirmYes').onclick = () => {
        dialog.remove();
        document.body.style.overflow = '';
        if (onConfirm) onConfirm();
    };
    
    document.getElementById('confirmNo').onclick = () => {
        dialog.remove();
        document.body.style.overflow = '';
        if (onCancel) onCancel();
    };
    
    // Close on backdrop click
    dialog.addEventListener('click', function(e) {
        if (e.target === this) {
            dialog.remove();
            document.body.style.overflow = '';
            if (onCancel) onCancel();
        }
    });
}

// ============================================
// AUTO-SHOW ALERTS FROM URL PARAMS
// ============================================

function initAlerts() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('success')) {
        const messages = {
            'created': 'Data berhasil ditambahkan',
            'updated': 'Data berhasil diperbarui',
            'deleted': 'Data berhasil dihapus',
            'payment': 'Pembayaran berhasil diproses'
        };
        Toast.success(messages[urlParams.get('success')] || 'Operasi berhasil');
        
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    if (urlParams.has('error')) {
        const messages = {
            'duplicate': 'Data sudah ada atau pembayaran duplikat',
            'notfound': 'Data tidak ditemukan',
            'unauthorized': 'Anda tidak memiliki akses',
            'timeout': 'Sesi Anda telah berakhir, silakan login kembali',
            'invalid': 'Data tidak valid'
        };
        Toast.error(messages[urlParams.get('error')] || 'Terjadi kesalahan');
        
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

function getCsrfToken() {
    const tokenInput = document.querySelector('[name="csrf_token"]');
    return tokenInput ? tokenInput.value : '';
}

function formatRupiah(amount) {
    return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return date.toLocaleDateString('id-ID', options);
}

// ============================================
// EXPORT FUNCTIONS
// ============================================

window.Toast = Toast;
window.Loading = Loading;
window.Confirm = Confirm;
window.openModal = openModal;
window.closeModal = closeModal;
window.editSiswa = editSiswa;
window.deleteSiswa = deleteSiswa;
window.editPetugas = editPetugas;
window.editKelas = editKelas;
window.editSpp = editSpp;
window.viewReceipt = viewReceipt;
window.printReceipt = printReceipt;
window.selectSiswa = selectSiswa;
window.formatRupiah = formatRupiah;
window.formatDate = formatDate;


// ============================================
// MOBILE FEATURES
// ============================================

// Mobile Sidebar Toggle
function initMobileSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (sidebarToggle && sidebar && sidebarOverlay) {
        // Toggle sidebar on mobile
        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            }
        });
        
        // Close sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Close sidebar on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
}

// Pull to Refresh
let startY = 0;
let isPulling = false;

function initPullToRefresh() {
    const mainContent = document.querySelector('.main-content');
    
    if (!mainContent || window.innerWidth > 768) return;
    
    mainContent.addEventListener('touchstart', function(e) {
        if (mainContent.scrollTop === 0) {
            startY = e.touches[0].pageY;
            isPulling = true;
        }
    });
    
    mainContent.addEventListener('touchmove', function(e) {
        if (!isPulling) return;
        
        const currentY = e.touches[0].pageY;
        const pullDistance = currentY - startY;
        
        if (pullDistance > 0 && pullDistance < 100) {
            e.preventDefault();
            // Show pull indicator
            showPullIndicator(pullDistance);
        }
    });
    
    mainContent.addEventListener('touchend', function(e) {
        if (!isPulling) return;
        
        const currentY = e.changedTouches[0].pageY;
        const pullDistance = currentY - startY;
        
        if (pullDistance > 80) {
            // Trigger refresh
            location.reload();
        }
        
        isPulling = false;
        hidePullIndicator();
    });
}

function showPullIndicator(distance) {
    let indicator = document.querySelector('.mobile-pull-refresh');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'mobile-pull-refresh';
        indicator.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        `;
        document.querySelector('.main-content').prepend(indicator);
    }
    
    if (distance > 80) {
        indicator.classList.add('pulling');
    } else {
        indicator.classList.remove('pulling');
    }
}

function hidePullIndicator() {
    const indicator = document.querySelector('.mobile-pull-refresh');
    if (indicator) {
        indicator.remove();
    }
}

// Touch Swipe for Cards
function initSwipeGestures() {
    const cards = document.querySelectorAll('.stat-card, .card');
    
    cards.forEach(card => {
        let startX = 0;
        let currentX = 0;
        
        card.addEventListener('touchstart', function(e) {
            startX = e.touches[0].pageX;
        });
        
        card.addEventListener('touchmove', function(e) {
            currentX = e.touches[0].pageX;
            const diff = currentX - startX;
            
            if (Math.abs(diff) > 10) {
                card.style.transform = `translateX(${diff}px)`;
                card.style.transition = 'none';
            }
        });
        
        card.addEventListener('touchend', function(e) {
            const diff = currentX - startX;
            
            card.style.transition = 'transform 0.3s ease';
            card.style.transform = 'translateX(0)';
            
            // Trigger action if swipe is significant
            if (Math.abs(diff) > 100) {
                // Add swipe action here if needed
                console.log('Swiped:', diff > 0 ? 'right' : 'left');
            }
        });
    });
}

// Mobile FAB (Floating Action Button)
function initMobileFAB() {
    const fab = document.querySelector('.mobile-fab');
    
    if (fab) {
        fab.addEventListener('click', function() {
            // Get current page context
            const currentPath = window.location.pathname;
            
            if (currentPath.includes('siswa') && !currentPath.includes('admin')) {
                // Siswa: Go to create
                window.location.href = '/admin/siswa/create';
            } else if (currentPath.includes('petugas') && !currentPath.includes('pembayaran')) {
                // Petugas list: Go to create
                window.location.href = '/admin/petugas/create';
            } else if (currentPath.includes('pembayaran')) {
                // Payment: Go to create
                window.location.href = '/pembayaran/create';
            }
        });
    }
}

// Haptic Feedback (if supported)
function hapticFeedback(type = 'light') {
    if ('vibrate' in navigator) {
        switch(type) {
            case 'light':
                navigator.vibrate(10);
                break;
            case 'medium':
                navigator.vibrate(20);
                break;
            case 'heavy':
                navigator.vibrate(50);
                break;
        }
    }
}

// Add haptic to buttons
function initHapticFeedback() {
    const buttons = document.querySelectorAll('.btn, .mobile-nav-item, .nav-link');
    
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            hapticFeedback('light');
        });
    });
}

// Mobile Search Enhancement
function initMobileSearch() {
    const searchInput = document.querySelector('.mobile-search input');
    
    if (searchInput) {
        // Auto-focus on mobile
        if (window.innerWidth <= 768) {
            searchInput.addEventListener('focus', function() {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        }
    }
}

// Prevent zoom on input focus (iOS)
function preventZoomOnFocus() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            if (window.innerWidth <= 768) {
                const viewport = document.querySelector('meta[name=viewport]');
                if (viewport) {
                    viewport.setAttribute('content', 'width=device-width, initial-scale=1, maximum-scale=1');
                }
            }
        });
        
        input.addEventListener('blur', function() {
            if (window.innerWidth <= 768) {
                const viewport = document.querySelector('meta[name=viewport]');
                if (viewport) {
                    viewport.setAttribute('content', 'width=device-width, initial-scale=1');
                }
            }
        });
    });
}

// Initialize all mobile features
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth <= 768) {
        initMobileSidebar();
        initPullToRefresh();
        initSwipeGestures();
        initMobileFAB();
        initHapticFeedback();
        initMobileSearch();
        preventZoomOnFocus();
    }
    
    // Re-init on resize
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 768) {
            initMobileSidebar();
            initPullToRefresh();
            initSwipeGestures();
            initMobileFAB();
            initHapticFeedback();
            initMobileSearch();
            preventZoomOnFocus();
        }
    });
});

// Service Worker for PWA (Progressive Web App)
if ('serviceWorker' in navigator && window.innerWidth <= 768) {
    window.addEventListener('load', function() {
        // Register service worker if available
        // navigator.serviceWorker.register('/sw.js');
    });
}

// Add to Home Screen prompt
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    // Show install button if needed
    const installBtn = document.querySelector('.install-app-btn');
    if (installBtn) {
        installBtn.style.display = 'block';
        
        installBtn.addEventListener('click', () => {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                }
                deferredPrompt = null;
            });
        });
    }
});
