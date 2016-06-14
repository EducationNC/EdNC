<div class="wrap">
    <div id="icon-options-general" class="icon32 icon32-posts-canva_menu"><h2>Canva <?php _e("Settings", 'canva') ?></h2></div>

    <div class="canva_plugin_config">
        <form method="post" action="options.php">
            <?php settings_fields('canva-settings'); ?>
            <?php do_settings_sections('canva-settings'); ?>

            <div class="form_field">
                <label>Design Type</label>
                <?php $type = get_option('canva_design_type'); ?>
                <select name="canva_design_type">
                    <option value="pinterest"<?php echo ($type == "pinterest") ? ' selected' : '' ?>><?php _e("Pinterest", 'canva' ); ?></option>
                    <option value="socialMediaGraphic"<?php echo ($type == "socialMediaGraphic") ? ' selected' : '' ?>><?php _e("Social Media Graphic", 'canva' ); ?></option>
                    <option value="presentation"<?php echo ($type == "presentation") ? ' selected' : '' ?>><?php _e("Presentation", 'canva' ); ?></option>
                    <option value="poster"<?php echo ($type == "poster") ? ' selected' : '' ?>><?php _e("Poster", 'canva' ); ?></option>
                    <option value="photoCollage"<?php echo ($type == "photoCollage") ? ' selected' : '' ?>><?php _e("Photo Collage", 'canva' ); ?></option>
                    <option value="facebookCover"<?php echo ($type == "facebookCover") ? ' selected' : '' ?>><?php _e("Facebook Cover", 'canva' ); ?></option>
                    <option value="blogGraphic"<?php echo ($type == "blogGraphic") ? ' selected' : '' ?>><?php _e("Blog Graphic", 'canva' ); ?></option>
                    <option value="a4"<?php echo ($type == "a4") ? ' selected' : '' ?>><?php _e("A4", 'canva' ); ?></option>
                    <option value="card"<?php echo ($type == "card") ? ' selected' : '' ?>><?php _e("Card", 'canva' ); ?></option>
                    <option value="businessCard"<?php echo ($type == "businessCard") ? ' selected' : '' ?>><?php _e("Business Card", 'canva' ); ?></option>
                    <option value="invitation"<?php echo ($type == "invitation") ? ' selected' : '' ?>><?php _e("Invitation", 'canva' ); ?></option>
                    <option value="facebookAd"<?php echo ($type == "facebookAd") ? ' selected' : '' ?>><?php _e("Facebook Ad", 'canva' ); ?></option>
                </select>
            </div>

            <div class="form_field">
                <label>API Key (optional)</label>
                <?php $api_key = get_option('canva_api_key'); ?>
                <input type="text" name="canva_api_key" value="<?php echo $api_key ?>" /> &nbsp; <a target="_blank" href="http://about.canva.com/button/#get-canva-button">Request an API Key &raquo;</a>
            </div>

            <?php submit_button(__('Save Settings', 'canva'), 'primary', 'submit', false, array('id'=>'canva_settings_bottom_submit')); ?>
        </form>
    </div>

    <br class="clear">
</div>