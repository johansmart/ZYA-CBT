<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Token
		<small>Membuat token untuk Tes</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo site_url() ?>/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Token</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-xs-12">
            <?php echo form_open($url.'/token','id="form-token"'); ?>
                <div class="callout callout-info">
                    <h4>Perhatian</h4>
                    <p>Silahkan klik Generate Token untuk mendapatkan token yang akan diberikan ke user. Masa aktif Token berlaku selama satu hari.</p>
                </div>
                <div class="box">
                    <div class="box-header with-border">
    					<div class="box-title">Generate Token</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-6">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3><span id="isi-token">0</span></h3>
                                    <p>Token Tes</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-barcode"></i>
                                </div>
                            </div>   
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Masa Aktif</label>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm" id="token-lifetime" name="token-lifetime">
                                        <option value="1">1 Hari</option>
                                        <option value="5">5 menit</option>
                                        <option value="15">15 menit</option>
                                        <option value="30">30 menit</option>
                                        <option value="60">1 Jam</option>
                                        <option value="120">2 Jam</option>
                                        <option value="240">4 Jam</option>
                                    </select>
                                    <p class="help-block">Masa Aktif Token</p>
                                </div>
                            </div> 
                        </div>
                        <div class="col-xs-3"></div>   
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right" id="import">Generate Token</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
    					<div class="box-title">Daftar Token Hari Ini</div>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <table id="table-token" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Token</th>
                                    <th>Waktu Generate</th>
                                    <th>Masa Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> </td>
									<td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
        </div>
    </div>
</section><!-- /.content -->



<script lang="javascript">
    function refresh_table(){
        $('#table-token').dataTable().fnReloadAjax();
    }

    $(function(){
        $('#form-token').submit(function(){
            $("#modal-proses").modal('show');
            $.ajax({
                    url:"<?php echo site_url().'/'.$url; ?>/token",
                    type:"POST",
                    data:$('#form-token').serialize(),
                    cache: false,
                    success:function(respon){
                        var obj = $.parseJSON(respon);
                        if(obj.status==1){
                            $("#modal-proses").modal('hide');
                            $("#isi-token").html(obj.token);
                            notify_success(obj.pesan);
                            refresh_table();
                        }else{
                            $("#modal-proses").modal('hide');
                            $('#form-pesan').html(pesan_err(obj.pesan));
                        }
                    }
            });
            return false;
        });

        $('#table-token').DataTable({
                  "paging": true,
                  "iDisplayLength":10,
                  "bProcessing": false,
                  "bServerSide": true, 
                  "searching": true,
                  "aoColumns": [
    					{"bSearchable": false, "bSortable": false, "sWidth":"20px"},
    					{"bSearchable": false, "bSortable": false},
    					{"bSearchable": false, "bSortable": false},
                        {"bSearchable": false, "bSortable": false}],
                  "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable/",
                  "autoWidth": false
         }); 
    });
</script>