<?php
// Rekap publik semester I 2026. Angka contoh ini terpisah dari data pengguna.
$permohonan = ['Jan' => 148, 'Feb' => 186, 'Mar' => 171, 'Apr' => 214, 'Mei' => 247, 'Jun' => 282];
$total = array_sum($permohonan);
$selesai = 1092;
$persenSelesai = 100 * $selesai / $total;
?>
<section class="service-report" aria-labelledby="report-title">
	<header class="report-heading">
		<p class="kicker">Statistik pelayanan</p>
		<h1 id="report-title">Dashboard layanan</h1>
		<p>Rekap permohonan kementerian <span class="period">Januari &ndash; Juni 2026</span></p>
	</header>
	<dl class="metrics">
		<div><dt>Permohonan masuk</dt><dd><?= number_format($total, 0, ',', '.') ?></dd></div>
		<div><dt>Selesai</dt><dd><?= number_format($selesai, 0, ',', '.') ?></dd></div>
		<div><dt>Dalam proses</dt><dd><?= number_format($total - $selesai, 0, ',', '.') ?></dd></div>
	</dl>
	<figure class="report-card">
		<figcaption>Permohonan per bulan</figcaption>
		<ul class="columns">
			<?php foreach ($permohonan as $bulan => $jumlah): ?>
				<li>
					<div class="plot"><span class="bar" style="height: <?= round(100 * $jumlah / max($permohonan)) ?>%"><b><?= $jumlah ?></b></span></div>
					<span class="month"><?= $bulan ?></span>
				</li>
			<?php endforeach ?>
		</ul>
	</figure>
	<section class="completion report-card" aria-labelledby="completion-title">
		<div><h2 id="completion-title">Penyelesaian permohonan</h2><strong><?= number_format($persenSelesai, 1, ',', '.') ?>%</strong></div>
		<meter min="0" max="<?= $total ?>" value="<?= $selesai ?>" aria-labelledby="completion-title"></meter>
		<p><?= number_format($selesai, 0, ',', '.') ?> dari <?= number_format($total, 0, ',', '.') ?> permohonan telah selesai.</p>
	</section>
</section>
