<main class="main-content">
    <div class="container">
        <div class="page-header">
            <h1>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 32px; height: 32px; display: inline; vertical-align: middle; margin-right: 0.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Laporan Pembayaran SPP
            </h1>
        </div>
        
        <div class="card">
            <div class="card-body">
                <form method="GET" class="filter-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select id="tahun" name="tahun" class="form-select" onchange="this.form.submit()">
                                <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <a href="?export=csv&tahun=<?= $tahun ?>" class="btn btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export CSV
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="stats-section">
            <h2 style="margin-bottom: var(--spacing-lg);">Statistik Pembayaran Tahun <?= $tahun ?></h2>
            
            <div id="chartContainer" style="margin: var(--spacing-xl) 0;">
                <svg id="monthlyChart" width="100%" height="300" viewBox="0 0 800 300" style="background: var(--surface); border-radius: var(--radius-lg); padding: var(--spacing-lg);"></svg>
            </div>
            
            <div class="card">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $bulanNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            $totalAmount = 0;
                            $totalCount = 0;
                            
                            $statsMap = [];
                            foreach ($monthlyStats as $stat) {
                                $statsMap[$stat['bulan_dibayar']] = $stat;
                            }
                            
                            for ($m = 1; $m <= 12; $m++):
                                $stat = $statsMap[$m] ?? ['count' => 0, 'total' => 0];
                                $totalAmount += $stat['total'];
                                $totalCount += $stat['count'];
                            ?>
                            <tr>
                                <td><strong><?= $bulanNames[$m] ?></strong></td>
                                <td><?= $stat['count'] ?> transaksi</td>
                                <td style="color: var(--success); font-weight: 600;">Rp <?= number_format($stat['total']) ?></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background: var(--gray-50); font-weight: 700;">
                                <th>Total</th>
                                <th><?= $totalCount ?> transaksi</th>
                                <th style="color: var(--primary); font-size: 1.125rem;">Rp <?= number_format($totalAmount) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Simple SVG Bar Chart
document.addEventListener('DOMContentLoaded', function() {
    const data = <?= json_encode(array_values($statsMap)) ?>;
    const svg = document.getElementById('monthlyChart');
    const width = 800;
    const height = 300;
    const padding = 40;
    const barWidth = (width - 2 * padding) / 12;
    
    const maxValue = Math.max(...data.map(d => d.total || 0), 1);
    
    // Draw bars
    for (let i = 0; i < 12; i++) {
        const stat = data.find(d => d.bulan_dibayar == (i + 1)) || {total: 0};
        const barHeight = (stat.total / maxValue) * (height - 2 * padding);
        const x = padding + i * barWidth;
        const y = height - padding - barHeight;
        
        const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        rect.setAttribute('x', x + 5);
        rect.setAttribute('y', y);
        rect.setAttribute('width', barWidth - 10);
        rect.setAttribute('height', barHeight);
        rect.setAttribute('fill', '#4F46E5');
        rect.setAttribute('rx', '4');
        svg.appendChild(rect);
        
        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        text.setAttribute('x', x + barWidth / 2);
        text.setAttribute('y', height - padding + 20);
        text.setAttribute('text-anchor', 'middle');
        text.setAttribute('font-size', '12');
        text.setAttribute('fill', '#6B7280');
        text.textContent = i + 1;
        svg.appendChild(text);
    }
});
</script>
