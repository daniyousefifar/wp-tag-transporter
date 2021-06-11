<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://yousefifar.dev
 * @since      1.0.0
 *
 * @package    Wp_Tag_Transporter
 * @subpackage Wp_Tag_Transporter/views
 */
?>

<div class="wp_tag_transporter_content">
    <h1 class="wp_tag_transporter_title"><?php _e('Tag Transporter', $this->plugin_name); ?></h1>
    <div class="wp_tag_transporter_wrapper">
        <div class="wp_tag_transporter_wrapper__tags">
            <h4><?php _e('Tag', $this->plugin_name); ?></h4>
            <select class="js-wp-tag-transporter-tags uk-width-1-1"></select>
        </div>

        <div class="wp_tag_transporter_wrapper__taxonomies">
            <h4><?php _e('Taxonomy', $this->plugin_name); ?></h4>
            <select class="js-wp-tag-transporter-taxonomies uk-width-1-1"></select>
        </div>

        <div class="wp_tag_transporter_wrapper__btn">
            <button type="button" class="js-wp_tag_transporter_action__wrapper--btn">
		        <?php _e('Transport', $this->plugin_name); ?>
            </button>
        </div>
    </div>
</div>