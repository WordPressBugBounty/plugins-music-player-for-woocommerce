<?php
if ( ! defined( 'WCMP_PLUGIN_URL' ) ) {
	echo 'Direct access not allowed.';
	exit; }

// include resources
wp_enqueue_style( 'wcmp-admin-style', plugin_dir_url( __FILE__ ) . '../css/style.admin.css', array(), '1.0.175' );

if (
	isset( $_REQUEST['post'] ) &&
	is_numeric( $_REQUEST['post'] ) &&
	( $post_id = intval( $_REQUEST['post'] ) ) &&
	( $check_post = get_post( $post_id ) )
) {
	$post = $check_post;
}

if ( empty( $post ) ) {
	global $post;
}

$enable_player   = $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_enable_player', false );
$show_in         = $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_show_in', 'all' );
$player_style    = $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_player_layout', WCMP_DEFAULT_PLAYER_LAYOUT );
$single_player   = $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_single_player', WCMP_DEFAULT_SINGLE_PLAYER );
$volume          = $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_player_volume', WCMP_DEFAULT_PLAYER_VOLUME );
$player_controls = $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_player_controls', WCMP_DEFAULT_PLAYER_CONTROLS );
$player_title    = intval( $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_player_title', 1 ) );
$merge_grouped   = intval( $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_merge_in_grouped', 0 ) );
$play_all        = intval(
	$GLOBALS['WooCommerceMusicPlayer']->get_product_attr(
		$post->ID,
		'_wcmp_play_all',
		// This option is only for compatibility with versions previous to 1.0.28
						$GLOBALS['WooCommerceMusicPlayer']->get_product_attr(
							$post->ID,
							'play_all',
							0
						)
	)
);
$loop     = intval( $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_loop', 0 ) );
$preload  = $GLOBALS['WooCommerceMusicPlayer']->get_product_attr(
	$post->ID,
	'_wcmp_preload',
	$GLOBALS['WooCommerceMusicPlayer']->get_product_attr(
		$post->ID,
		'preload',
		'none'
	)
);
$on_cover = intval( $GLOBALS['WooCommerceMusicPlayer']->get_product_attr( $post->ID, '_wcmp_on_cover', 0 ) );
?>
<input type="hidden" name="wcmp_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wcmp_updating_product' ) ); ?>" />
<table class="widefat" style="border-left:0;border-right:0;border-bottom:0;padding-bottom:0;">
	<tr>
		<td>
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
			<div class="wcmp-highlight-box">
				<?php
				_e( '<p>The player uses the audio files associated with the product.</p>', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput
				_e( '<hr style="border: 0;border-top: 1px solid #E6DB55;"><p style="font-size:1.2em;"><i>The free distribution of the "Music Player for WooCommerce" plugin requires configuring the product as downloadable and including an audio file in the list of files to download.</i></p><hr style="border: 0;border-top: 1px solid #E6DB55;">', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput

				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					_e( '<p style="font-size:1.1em;"><i>If you designed the products template with <b>Elementor</b>, insert the playlist shortcode in the template without the <i>products_ids</i> attribute:<br><b>[wcmp-playlist player_style="mejs-classic"]</b>.</i></p><hr style="border: 0;border-top: 1px solid #E6DB55;">', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput
				}

				_e( '<p>If you want to protect the audio files, tick the "Protect file" checkbox. In this case, the plugin will generate a shortened version of the audio files for demo purposes. The plugin determines the size of the demo audio files based on the value entered in the <b>"Percentage of audio used for protected playbacks"</b> attribute.</p>', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			<p><?php esc_html_e( 'The security feature and particular files for demo are only available in the PRO version of the plugin', 'music-player-for-woocommerce' ); ?>. <a target="_blank" href="https://wcmp.dwbooster.com/download"><?php esc_html_e( 'CLICK HERE TO GET THE PRO VERSION OF THE PLUGIN', 'music-player-for-woocommerce' ); ?></a></p>
			<p><?php _e( 'For testing the premium version of the plugin, visit the online demo:<br/> <a href="https://demos.dwbooster.com/music-player-for-woocommerce/wp-login.php" target="_blank">Administration area: Click to access the administration area demo</a><br/><a href="https://demos.dwbooster.com/music-player-for-woocommerce/" target="_blank">Public page: Click to visit the WooCommerce Store</a>', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			</div>
			<?php endif; ?>
			<div class="wcmp-highlight-box">
				<div id="wcmp_tips_header">
					<h3 style="margin-top:2px;margin-bottom:5px;cursor:pointer;" onclick="jQuery('#wcmp_tips_body').toggle();">
						<?php esc_html_e( '[+|-] Tips', 'music-player-for-woocommerce' ); ?>
					</h3>
				</div>
				<div id="wcmp_tips_body">
					<div class="wcmp-highlight-box">
						<a class="wcmp-tip"href="javascript:void(0);" onclick="jQuery(this).next('.wcmp-tip-text').toggle();">
						<?php esc_html_e( '[+|-] Using the audio files stored on Google Drive', 'music-player-for-woocommerce' ); ?>
						</a>
						<div class="wcmp-tip-text">
						<ul>
							<li>
								<p> -
								<?php
									esc_html_e(
										'Go to Drive, press the right click on the file to use, and select the option: ',
										'music-player-for-woocommerce'
									);
									?><b>"Get Shareable Link"</b>
								</p>
								<p>
								<?php
									esc_html_e(
										'The previous action will generate an url with the structure: ',
										'music-player-for-woocommerce'
									);
									?><b>https://drive.google.com/open?id=FILE_ID</b>
								</p>
							</li>
							<li>
								<p> -
									<?php
									esc_html_e(
										'Knowing the FILE_ID, extracted from the previous URL, enter the URL below, into the WooCommerce product, to allow the Music Player accessing to it:',
										'music-player-for-woocommerce'
									);
									?>
								</p>
								<p>
									<b>https://drive.google.com/uc?export=download&id=FILE_ID&.mp3</b>
								</p>
								<p>
									<?php
									_e( '<b>Note:</b> Pay attention to the use of the fake parameter: <b>&.mp3</b> as the last one in the URL', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput
									?>
								</p>
							</li>
						</div>
					</div>
					<div class="wcmp-highlight-box">
						<a class="wcmp-tip"href="javascript:void(0);" onclick="jQuery(this).next('.wcmp-tip-text').toggle();">
						<?php esc_html_e( '[+|-] Using the audio files stored on DropBox', 'music-player-for-woocommerce' ); ?>
						</a>
						<div class="wcmp-tip-text">
						<ul>
							<li>
								<p> -
								<?php
									esc_html_e(
										'Sign in to ',
										'music-player-for-woocommerce'
									);
									?><a href="https://www.dropbox.com/login" target="_blank">dropbox.com </a>
								</p>
							</li>
							<li>
								<p> -
								<?php
									_e( "Hover your cursor over the file or folder you'd like to share and click <b>Share</b> when it appears.", 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput
								?>
								</p>
							</li>
							<li>
								<p> -
								<?php
									esc_html_e(
										"If a link hasn't been created, click Create a link. (If a link was already created, click Copy link)",
										'music-player-for-woocommerce'
									);
									?>
								</p>
								<p>
								<?php
									esc_html_e(
										'The link structure would be similar to:',
										'music-player-for-woocommerce'
									);
									?><br> https://www.dropbox.com/s/rycvgn8iokfedmo/file.mp3?dl=0
								</p>
							</li>
							<li>
								<p> -
								<?php
									esc_html_e( 'Enter the URL into the WooCommerce product with the following structure:', 'music-player-for-woocommerce' );
								?><br> https://www.dropbox.com/s/rycvgn8iokfedmo/file.mp3?dl=1&.mp3
								</p>
								<p>
									<?php
									_e( '<b>Note:</b> Pay attention to the use of the fake parameter: <b>&.mp3</b> as the last one in the URL. Furthermore, the parameter <b>dl=0</b>, has been modified as <b>dl=1</b>', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput
									?>
								</p>
							</li>
						</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table class="widefat wcmp-player-settings" style="border:1px solid #e1e1e1;">
				<tr>
					<td><label for="_wcmp_enable_player"><?php esc_html_e( 'Include music player', 'music-player-for-woocommerce' ); ?></label></td>
					<td><div class="wcmp-tooltip"><span class="wcmp-tooltiptext"><?php esc_html_e( 'The player is shown only if the product is "downloadable", and there is at least an audio file between the "Downloadable files"', 'music-player-for-woocommerce' ); ?></span><input aria-label="<?php esc_attr_e( 'Enable player', 'music-player-for-woocommerce' ); ?>" type="checkbox" id="_wcmp_enable_player" name="_wcmp_enable_player" <?php echo ( ( $enable_player ) ? 'checked' : '' ); ?> /></div></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Include in', 'music-player-for-woocommerce' ); ?></td>
					<td>
						<label><input aria-label="<?php esc_attr_e( 'Include on products pages', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_show_in" value="single" <?php echo ( ( 'single' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'single-entry pages <i>(Product\'s page only)</i>', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></label><br />

						<label><input aria-label="<?php esc_attr_e( 'Include on multiple-entry pages', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_show_in" value="multiple" <?php echo ( ( 'multiple' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'multiple entries pages <i>(Shop pages, archive pages, but not in the product\'s page)</i>', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></label><br />

						<label><input aria-label="<?php esc_attr_e( 'Include on product and multiple-entry pages', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_show_in" value="all" <?php echo ( ( 'all' == $show_in ) ? 'checked' : '' ); ?> />
						<?php _e( 'all pages <i>(with single or multiple-entries)</i>', 'music-player-for-woocommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></label>
					</td>
				</tr>
				<tr>
					<td><label for="_wcmp_merge_in_grouped"><?php esc_html_e( 'Merge in grouped products', 'music-player-for-woocommerce' ); ?></label></td>
					<td><input aria-label="<?php esc_attr_e( 'Merge in grouped products', 'music-player-for-woocommerce' ); ?>" type="checkbox" id="_wcmp_merge_in_grouped" name="_wcmp_merge_in_grouped" <?php echo ( ( $merge_grouped ) ? 'checked' : '' ); ?> /><br /><em><?php esc_html_e( 'In grouped products, display the "Add to cart" buttons and quantity fields in the players rows', 'music-player-for-woocommerce' ); ?></em></td>
				</tr>
				<tr>
					<td valign="top"><?php esc_html_e( 'Player layout', 'music-player-for-woocommerce' ); ?></td>
					<td>
						<table>
							<tr>
								<td><input aria-label="<?php esc_attr_e( 'Skin 1', 'music-player-for-woocommerce' ); ?>" id="_wcmp_player_layout_01" name="_wcmp_player_layout" type="radio" value="mejs-classic" <?php echo ( ( 'mejs-classic' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td style="width:100%;padding-left:20px;"><label for="_wcmp_player_layout_01"><img alt="<?php esc_attr_e( 'Skin 1', 'music-player-for-woocommerce' ); ?>" src="<?php print esc_url( WCMP_PLUGIN_URL ); ?>/views/assets/skin1.png" /></label></td>
							</tr>

							<tr>
								<td><input aria-label="<?php esc_attr_e( 'Skin 2', 'music-player-for-woocommerce' ); ?>" id="_wcmp_player_layout_02" name="_wcmp_player_layout" type="radio" value="mejs-ted" <?php echo ( ( 'mejs-ted' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td style="width:100%;padding-left:20px;"><label for="_wcmp_player_layout_02"><img alt="<?php esc_attr_e( 'Skin 2', 'music-player-for-woocommerce' ); ?>" src="<?php print esc_url( WCMP_PLUGIN_URL ); ?>/views/assets/skin2.png" /></label></td>
							</tr>

							<tr>
								<td><input aria-label="<?php esc_attr_e( 'Skin 3', 'music-player-for-woocommerce' ); ?>" id="_wcmp_player_layout_03" name="_wcmp_player_layout" type="radio" value="mejs-wmp" <?php echo ( ( 'mejs-wmp' == $player_style ) ? 'checked' : '' ); ?> /></td>
								<td style="width:100%;padding-left:20px;"><label for="_wcmp_player_layout_03"><img alt="<?php esc_attr_e( 'Skin 3', 'music-player-for-woocommerce' ); ?>" src="<?php print esc_url( WCMP_PLUGIN_URL ); ?>/views/assets/skin3.png" /></label></td>
							</tr>

							<tr>
								<td colspan="2" style="border-top: 1px solid #DADADA;border-bottom: 1px solid #DADADA;"><label><input aria-label="<?php esc_attr_e( 'Show a single player instead of one player per audio file.', 'music-player-for-woocommerce' ); ?>" name="_wcmp_single_player" type="checkbox" <?php echo ( ( $single_player ) ? 'checked' : '' ); ?> />
								<span style="padding-left:20px;"><?php esc_html_e( 'Show a single player instead of one player per audio file.', 'music-player-for-woocommerce' ); ?></span></label>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<?php esc_html_e( 'Preload', 'music-player-for-woocommerce' ); ?>
					</td>
					<td>
						<label><input aria-label="<?php esc_attr_e( 'Preload - none', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_preload" value="none" <?php if ( 'none' == $preload ) {
							echo 'CHECKED';} ?> /> None</label><br />
						<label><input aria-label="<?php esc_attr_e( 'Preload - metadata', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_preload" value="metadata" <?php if ( 'metadata' == $preload ) {
							echo 'CHECKED';} ?> /> Metadata</label><br />
						<label><input aria-label="<?php esc_attr_e( 'Preload - auto', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_preload" value="auto" <?php if ( 'auto' == $preload ) {
							echo 'CHECKED';} ?> /> Auto</label><br />
					</td>
				</tr>
				<tr>
					<td>
						<label for="_wcmp_play_all"><?php esc_html_e( 'Play all', 'music-player-for-woocommerce' ); ?></label>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Play all', 'music-player-for-woocommerce' ); ?>" type="checkbox" id="_wcmp_play_all" name="_wcmp_play_all" <?php if ( ! empty( $play_all ) ) {
							echo 'CHECKED';} ?> />
					</td>
				</tr>
				<tr>
					<td>
						<label for="_wcmp_loop"><?php esc_html_e( 'Loop', 'music-player-for-woocommerce' ); ?></label>
					</td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Loop', 'music-player-for-woocommerce' ); ?>" type="checkbox" id="_wcmp_loop" name="_wcmp_loop" <?php if ( ! empty( $loop ) ) {
							echo 'CHECKED';} ?> />
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Player volume (from 0 to 1)', 'music-player-for-woocommerce' ); ?></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Player volume', 'music-player-for-woocommerce' ); ?>" type="number" name="_wcmp_player_volume" min="0" max="1" step="0.01" value="<?php echo esc_attr( $volume ); ?>" />
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Player controls', 'music-player-for-woocommerce' ); ?></td>
					<td>
						<label><input aria-label="<?php esc_attr_e( 'Play/pause button', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_player_controls" value="button" <?php echo ( ( 'button' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'the play/pause button only', 'music-player-for-woocommerce' ); ?></label><br />
						<label><input aria-label="<?php esc_attr_e( 'All controls', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_player_controls" value="all" <?php echo ( ( 'all' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'all controls', 'music-player-for-woocommerce' ); ?></label><br />
						<label><input aria-label="<?php esc_attr_e( 'Depending on context', 'music-player-for-woocommerce' ); ?>" type="radio" name="_wcmp_player_controls" value="default" <?php echo ( ( 'default' == $player_controls ) ? 'checked' : '' ); ?> /> <?php esc_html_e( 'the play/pause button only, or all controls depending on context', 'music-player-for-woocommerce' ); ?></label>
						<div class="wcmp-on-cover" style="margin-top:10px;">
							<label><input aria-label="<?php esc_attr_e( 'On cover', 'music-player-for-woocommerce' ); ?>" type="checkbox" name="_wcmp_player_on_cover" value="default" <?php
							echo ( ( ! empty( $on_cover ) && ( 'button' == $player_controls || 'default' == $player_controls ) ) ? 'checked' : '' );
							?> />
							<?php esc_html_e( 'for play/pause button players display them on cover images.', 'music-player-for-woocommerce' ); ?>
							<i>
							<?php
							esc_html_e( '(This feature is experimental, and will depend on the theme active on the website.)', 'music-player-for-woocommerce' );
							?>
							</i></label>
						</div>
					</td>
				</tr>
				<tr>
					<td><label for="_wcmp_player_title"><?php esc_html_e( 'Display the player\'s title', 'music-player-for-woocommerce' ); ?></label></td>
					<td>
						<input aria-label="<?php esc_attr_e( 'Display the player title', 'music-player-for-woocommerce' ); ?>" type="checkbox" id="_wcmp_player_title" name="_wcmp_player_title" <?php echo ( ( ! empty( $player_title ) ) ? 'checked' : '' ); ?> />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="color:red;"><?php esc_html_e( 'The security feature is only available in the PRO version of the plugin', 'music-player-for-woocommerce' ); ?>. <a target="_blank" href="https://wcmp.dwbooster.com"><?php esc_html_e( 'CLICK HERE TO GET THE PRO VERSION OF THE PLUGIN', 'music-player-for-woocommerce' ); ?></a></td>
				</tr>
				<tr>
					<td style="color:#DDDDDD;"><?php esc_html_e( 'Protect the file', 'music-player-for-woocommerce' ); ?></td>
					<td><input aria-label="<?php esc_attr_e( 'Protect the file', 'music-player-for-woocommerce' ); ?>" type="checkbox" DISABLED /></td>
				</tr>
				<tr valign="top">
					<td style="color:#DDDDDD;"><?php esc_html_e( 'Percent of audio used for protected playbacks', 'music-player-for-woocommerce' ); ?></td>
					<td style="color:#DDDDDD;">
						<input aria-label="<?php esc_attr_e( 'Percent of audio used for protected playbacks', 'music-player-for-woocommerce' ); ?>" type="number" DISABLED /> % <br /><br />
						<em><?php esc_html_e( 'To prevent unauthorized copying of audio files, the files will be partially accessible', 'music-player-for-woocommerce' ); ?></em>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table class="widefat" style="border:0;padding-bottom:20px;">
	<tr valign="top">
		<td style="color:#DDDDDD;">
			<table class="widefat wcmp-player-demos" style="border:1px solid #e1e1e1;">
				<tr valign="top">
					<td style="color:#DDDDDD;"><input aria-label="<?php esc_attr_e( 'Own demo files', 'music-player-for-woocommerce' ); ?>" type="checkbox" disabled /> <?php esc_html_e( 'Select my own demo files', 'music-player-for-woocommerce' ); ?></td>
				</tr>
				<tr valign="top" class="wcmp-demo-files">
					<td>
						<div style="color:#DDDDDD;margin-bottom:15px;"><b><?php esc_html_e( 'Demo files', 'music-player-for-woocommerce' ); ?></b></div>
						<table class="widefat">
							<thead>
								<tr>
									<th style="color:#DDDDDD;"><?php esc_html_e( 'Name', 'music-player-for-woocommerce' ); ?></th>
									<th colspan="2" style="color:#DDDDDD;"><?php esc_html_e( 'File URL', 'music-player-for-woocommerce' ); ?></th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<input aria-label="<?php esc_attr_e( 'File name', 'music-player-for-woocommerce' ); ?>" type="text" class="wcmp-file-name" placeholder="<?php esc_attr_e( 'File Name', 'music-player-for-woocommerce' ); ?>" disabled style="color:#DDDDDD;" />
									</td>
									<td>
										<input aria-label="<?php esc_attr_e( 'File URL', 'music-player-for-woocommerce' ); ?>" type="text" class="wcmp-file-url" placeholder="http://" disabled style="color:#DDDDDD;" />
									</td>
									<td width="1%" style="color:#DDDDDD;">
										<a href="javascript:void(0);" class="btn btn-default button wcmp-select-file" style="color:#DDDDDD;"><?php esc_html_e( 'Choose file', 'music-player-for-woocommerce' ); ?></a>
									</td>
									<td width="1%" style="color:#DDDDDD;">
										<a href="javascript:void(0);" class="wcmp-delete" style="color:#DDDDDD;"><?php esc_html_e( 'Delete', 'music-player-for-woocommerce' ); ?></a>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" style="color:#DDDDDD;">
										<a href="javascript:void(0);" class="button wcmp-add" style="color:#DDDDDD;"><?php esc_html_e( 'Add File', 'music-player-for-woocommerce' ); ?></a>
									</th>
								</tr>
							</tfoot>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script>jQuery(window).on('load', function(){
	var $ = jQuery;
	function coverSection()
	{
		var v = $('[name="_wcmp_player_controls"]:checked').val(),
			c = $('.wcmp-on-cover');
		if('default' == v || 'button' == v) c.show();
		else c.hide();
	};
	$(document).on('change', '[name="_wcmp_player_controls"]', function(){
		coverSection();
	});
	coverSection();
});</script>
<style>.wcmp-player-settings tr td:first-child{width:225px;}</style>
