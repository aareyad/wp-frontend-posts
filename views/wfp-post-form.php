<div id="wfp-postbox" class="wfp-postbox">
	<form action="<?php echo admin_url( 'admin-post.php' ); ?>" id="new_post" name="new_post" class="new-post" method="post">
		<input type="hidden" name="action" value="wfp_post_save" />
		<div class="wfp-form-container">
			<div class="wfp-left-content grid-item">
				<div>
					<label for="title"><?php _e('Title','wp-frontend-posts'); ?><span>*</span></label>
					<input type="text" id="title" value="" tabindex="1" size="20" name="title" required />
				</div>
				<div>
					<label for="content"><?php _e('Content','wp-frontend-posts'); ?></label>
					<?php
						$content = '';
						if (isset($_POST['submit'])) {
							$content = $_POST['content'];
						}
						$editor_settings = array(
							'textarea_name' => 'content',
							'textarea_rows' => 12,
							'media_buttons' => true
							
						);
						wp_editor($content, 'user-editor', $settings = $editor_settings);
					?>
				</div>
			</div>
			<div class="wfp-right-content grid-item">
				<div>
					<label for="category"><?php _e('Category','wp-frontend-posts'); ?></label>
					<div class="cat-list">
						<?php
							$terms = get_terms([
								'taxonomy' => 'category',
								'hide_empty' => false,
							]);
						?>
						<select id="wfp-category" class="postform" name="post_category[]">
							<option value="" selected disabled></option>
						<?php
							foreach($terms as $cat) {
								echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';
							}
						?>
						</select>
					</div>
				</div>
				<div>
					<label for="post_tags"><?php _e('Tags','wp-frontend-posts'); ?></label>
					<input type="text" value="" name="post_tags" id="post_tags" />
				</div>
				<div id="wfp-featured-image">
					<div id="wfp-featured-image-container"  class="featured-image-container wfp-image-preview">
						<img width="280" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/placeholder.png'; ?>" class="feature-image" alt="image">
					</div>
					<label class="custom-file-upload">
						<input id="wfp-feature-image" type="file"/>
						<input name="wfp_feature_image" type="hidden" id="wfp-feature-image-id" value=""/>
						<i class="fas fa-cloud-upload-alt"></i> <?php _e('Set Featured Image','wp-frontend-posts'); ?>
					</label>
				</div>
			</div>
			<?php wp_nonce_field( 'wfp-frontend-post' ); ?>
			<p>
				<input type="submit" value="Publish" tabindex="6" id="submit" name="submit" />
			</p>
		</div>
	</form>
</div>