<?php

if (isset($_POST["rmp-css-content"])) {
	file_put_contents(RMP_PLUGIN_DIR . "/css/rmp-playlist-style.css", $_POST["rmp-css-content"]);
	?>
	<div id="info" class="fade updated">
		<p><strong>Successfully updated the Playlist CSS</span></strong></p>
	</div>
<?php }

$cssContents = file_get_contents(RMP_PLUGIN_DIR . "/css/rmp-playlist-style.css");

?>
<div class="wrap">
	<h2>Remote My Project - Playlist Style</h2>
	<form name="<?php echo RMP_KEY . "form"; ?>" method="post" action="">
		<div id="poststuff">
			<div id="post-body">
				<div id="post-body-content">
					<div class="stuffbox">
						<h3 class="hndle"><span>Playlist CSS</span></h3>
						<div class="inside" style="margin: 10px;">
							<textarea class="rmp-css" name="rmp-css-content"><?php echo $cssContents; ?></textarea><br />
							<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$("textarea").tabby();
	});
</script>
<?php 

?>