<div class="container">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Browser yang Di Dukung
        <small> Browser yang didukung aplikasi</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="callout callout-info">
        <h4>Informasi</h4>
        <p>Untuk mengakses aplikasi pendataan, gunakan salah satu dari browser berikut melalui perangkat komputer PC ataupun Laptop : Mozilla Firefox, Google Chrome, Safari, atau Opera.
		</p>
    </div>
</section><!-- /.content -->
</div>

    <div class="modal" id="modal-proses" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
                    Data Sedang diproses...
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<script type="text/javascript">
    $(function () {
        $('#no-pendaftaran').focus();
        $("#no-un").inputmask();   
        
		$(document).bind("ajaxSend", function() {
		        $("#modal-proses").modal('show');
		    }).bind("ajaxStop", function() {
		        $("#modal-proses").modal('hide');
		    }).bind("ajaxError", function() {
		        $("#modal-proses").modal('hide');
		    });   
        
        $('#form-awal').submit(function(){
            
                $.ajax({
                    url:"<?php echo site_url()?>/welcome/first",
     			    type:"POST",
     			    data:$('#form-awal').serialize(),
     			    cache: false,
      		        success:function(respon){
         		    	var obj = $.parseJSON(respon);
      		            if(obj.status==1){
      		                window.open("<?php echo site_url()?>/welcome/second/"+obj.kunci1+"/"+obj.kunci2,"_self")
          		        }else{
                            $('#form-pesan').html(pesan_err(obj.error));
          		        }
         			}
      		});
            
      		return false;
        });    
    });
</script>