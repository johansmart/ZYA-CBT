<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Backup Data
		<small>Backup data Aplikasi Ujian Online meliputi database dan file yang diupload.</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url() ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Backup Data</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-xs-12">
            <div class="callout callout-info">
                <h4>Informasi</h4>
                <p>Lakukan Backup Data secara berkala. Lakukan Backup pada Database dan Data yang telah di upload untuk keamanan.</p>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
    					<div class="box-title">Backup Database</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <span id="form-pesan-database"></span>
                        <p>Klik tombol <b>Backup Database</b> untuk melakukan Backup database ZYA CBT. Simpan backup database pada tempat yang aman.</p>
                        <p>Database akan disimpan dalam archive. Extract terlebih dahulu sebelum melakukan restore database.</p>
                        <p>Restore database dapat menggunakan PHPMyAdmin atau software yang lain.</p>
                    </div>
					
					<div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="backup-database">Backup Database</button>
                    </div>
                </div>
        </div>
        <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
    					<div class="box-title">Backup Data Upload</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <span id="form-pesan-data-upload"></span>
                        <p>Klik tombol <b>Backup Data Upload</b> untuk melakukan Backup Data yang di Upload (gambar, audio) pada ZYA CBT. Simpan backup data pada tempat yang aman.</p>
                        <p>Data file yang di upload akan disimpan dalam bentuk archive.</p>
                        <p>Restore Data Upload dilakukan dengan meng-copy data ke folder uploads pada folder utama ZYA CBT</p>
                    </div>
					
					<div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="backup-data-upload">Backup Data Upload</button>
                    </div>
                </div>
        </div>
    </div>
</section><!-- /.content -->



<script lang="javascript">
    

    $(function(){
        $('#backup-database').click(function(){
            window.open("<?php echo site_url().'/'.$url; ?>/database");
        });

        $('#backup-data-upload').click(function(){
            window.open("<?php echo site_url().'/'.$url; ?>/data_upload");
        });
    });
</script>