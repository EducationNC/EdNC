<form method="POST" id="mailmunch-settings">
<?php $autoEmbed = $this->mailmunch_api->getSetting('auto_embed'); ?>
<div id="poststuff" class="wrap">
  <div id="post-body" class="metabox-holder columns-2">
    <div id="post-body-content">
      <h2>
        Settings | <?php echo $this->plugin_name; ?>
      </h2>

      <table class="wp-list-table widefat fixed posts settings-table">
        <tbody>
          <tr>
            <td class="inside-container" width="30%">
              <h3>Auto Embedding</h3>
              <p>If enabled, it will add blank div tags in your posts and pages so you can embed forms more easily.</p>
            </td>
            <td class="setting">
              <select name="auto_embed">
                <option value="yes"<?php if ($autoEmbed == 'yes' || empty($autoEmbed)) echo "selected=\"selected\""; ?>>Yes</option>
                <option value="no"<?php if ($autoEmbed == 'no') echo "selected=\"selected\""; ?>>No</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" name="Save" value="Save Settings" class="button button-primary" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div id="postbox-container-1" class="postbox-container">
      <div id="side-sortables" class="meta-box-sortables ui-sortable">
        <div class="postbox">
          <h3><span>Need Support?</span></h3>

          <div class="inside">
            <p>Need Help? <a href="https://mailmunch.zendesk.com/hc" target="_blank">Contact Support</a></p>

            <div class="video-trigger">
              <p>Watch our quick tour video:</p>
              <img src="<?php echo plugins_url( 'img/video.jpg', dirname(__FILE__) ) ?>" onclick="showVideo()" />
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
</form>