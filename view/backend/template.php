<div class="wrap">
    <h2>CM Video Lessons
        <?php if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'cmvl_pro' ) !== false ) { ?>
        <?php } else { ?>
            <a href="<?php echo esc_url( get_admin_url( '', 'admin.php?page=cmvl_pro' ) ); ?>" class="button button-primary" title="Click to Buy PRO">Upgrade to Pro</a>
        <?php } ?>
    </h2>
    <?php
    echo do_shortcode( '[cminds_free_activation id="CMVL"]' );
    ?>
    <div id="cminds_settings_container">
        <?php echo $nav; ?>
        <?php echo $content; ?>
    </div>
</div>
