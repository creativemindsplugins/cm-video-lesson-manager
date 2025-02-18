<div class="wrap">
    <h2>
		<?php
		if($title != '') {
			echo $title;
		} else {
			echo "CM Video Lessons";
		}
		?>
        <?php if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'CMVL_pro' ) !== false ) { ?>
		
        <?php } else { ?>
            <a href="<?php echo esc_url( get_admin_url( '', 'admin.php?page=CMVL_pro' ) ); ?>" class="button button-primary" title="Click to Buy PRO">Upgrade to Pro</a>
        <?php } ?>
    </h2>
    <?php
    echo do_shortcode( '[cminds_free_activation id="CMVL"]' );
    ?>
	<div class="show_hide_pro_options" style="position:absolute; right:20px; margin-top:8px;">
		<input onclick="jQuery('.onlyinpro').toggleClass('hide'); return false;" type="button" name="" value="Show/hide Pro options" class="button" />
	</div>
    <div id="cminds_settings_container">
        <?php echo $nav; ?>
        <?php echo $content; ?>
    </div>
</div>