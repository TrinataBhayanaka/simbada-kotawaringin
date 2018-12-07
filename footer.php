	
	<footer style="height:100px">
		<div>
            <center>
                <span style="color:white">Supported By : </span><br>
                <img src="<?=$url_rewrite?>/img/logo-gunadarma.png" width="70px">
                <img src="<?=$url_rewrite?>/img/logo-bssn.png" width="60px">
                <img src="<?=$url_rewrite?>/img/logo_bsre.png" width="130px">
            </center>
        </div>
	</footer>
</div>
<?php session_write_close();?>
      <!-- Bootstrap javascript -->
      <script src="<?php echo "$url_rewrite/"; ?>/js/bootstrap.min_simbada.js"></script>
      <script type="text/javascript">
      	$(document).ready(function() {

			$('[data-toggle="popover"]').popover();
		});
      </script>
</body>
</html>