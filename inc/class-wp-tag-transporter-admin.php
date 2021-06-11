<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://yousefifar.dev
 * @since      1.0.0
 *
 * @package    Wp_Tag_Transporter
 * @subpackage Wp_Tag_Transporter/inc
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Tag_Transporter
 * @subpackage Wp_Tag_Transporter/inc
 * @author     Daniel Yousefi Far <daniyousefifar@gmail.com>
 */
class Wp_Tag_Transporter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Tag_Transporter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Tag_Transporter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $pagenow;
		if ( ( $pagenow == 'tools.php' ) && ( $_GET['page'] == 'wp-tag-transporter' ) ) {
			wp_enqueue_style( $this->plugin_name . '_uikit', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/uikit.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '_select2', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/select2.min.css', array(), $this->version, 'all' );
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/wp-tag-transporter.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Tag_Transporter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Tag_Transporter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $pagenow;
		if ( ( $pagenow == 'tools.php' ) && ( $_GET['page'] == 'wp-tag-transporter' ) ) {
			wp_enqueue_script( $this->plugin_name . '_uikit', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/uikit.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '_select2', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/select2.min.js', array( 'jquery' ), $this->version, false );
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/wp-tag-transporter.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'wp_tag_transporter_object', [
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
			'nonce'                  => wp_create_nonce( 'ajax-nonce' ),
			'tags_placeholder'       => __( 'Select a tag', $this->plugin_name ),
			'taxonomies_placeholder' => __( 'Select a taxonomy', $this->plugin_name ),
		] );

	}

	/**
	 * Creates the sub menus for transporting tags.
	 */
	public function add_tag_transporter_menu_page() {
		add_submenu_page(
			'tools.php',
			__( 'Tag Transporter', $this->plugin_name ),
			__( 'Tag Transporter', $this->plugin_name ),
			'edit_posts',
			'wp-tag-transporter',
			array( $this, 'render_tag_transporter_menu' )
		);
	}

	/**
	 * Renders tag transporter menu page using
	 * the "tag_transporter.php" partial.
	 */
	public function render_tag_transporter_menu() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'views/tag_transporter.php';
	}

	/**
	 * wp_tag_transporter_load_tags Ajax Handler
	 */
	public function ajax_load_tags_handler() {
		$nonce = $_POST['nonce'];

		if ( ! isset( $nonce ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Busted!' );
		}

		$search_query = $_POST['search'];

		$args = [
			'taxonomy'   => 'post_tag',
			'orderby'    => 'id',
			'order'      => 'ASC',
			'hide_empty' => true,
			'fields'     => 'all',
			'name__like' => $search_query,
		];

		$terms   = get_terms( $args );
		$payload = [];

		foreach ( $terms as $term ) {
			$payload[] = [
				'id'   => $term->term_id,
				'text' => $term->name,
			];
		}

		$count = count( $terms );

		wp_send_json_success( [
			'payload' => $payload,
			'count'   => $count,
		], 200 );
	}

	/**
	 * wp_tag_transporter_load_taxonomies Ajax Handler
	 */
	public function ajax_load_taxonomies_handler() {
		$nonce = $_POST['nonce'];

		if ( ! isset( $nonce ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Busted!' );
		}

		$search_query = $_POST['search'];

		$taxonomies = get_taxonomies( [
			'public' => true,
		] );
		$search_key = array_search( $search_query, $taxonomies );
		$payload    = [];

		foreach ( $taxonomies as $key => $val ) {
			$taxonomy  = get_taxonomy( $val );
			$payload[] = [
				'id'   => $val,
				'text' => $taxonomy->label,
			];
		}

		$count = count( $taxonomies );

		wp_send_json_success( [
			'payload' => $payload,
			'count'   => $count,
		], 200 );
	}

	public function ajax_process_request_handler() {
		$nonce = $_POST['nonce'];

		if ( ! isset( $nonce ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Busted!' );
		}

		$tag_id   = $_POST['tag'];
		$taxonomy = $_POST['taxonomy'];

		$tag = get_tag( $tag_id );
		if ( ! $tag ) {
			wp_send_json_error( [
				'message' => 'Selected Tag Invalid',
			] );
			exit();
		}

		$term = get_term_by( 'name', $tag->name, $taxonomy );
		if ( ! $term ) {
			$term = wp_insert_term( $tag->name, $taxonomy, [
				'description' => $tag->description,
				'slug'        => $tag->slug,
			] );
		}

		$the_query = new WP_Query( [
			'tag_id'         => $tag_id,
			'post_type'      => 'post',
			'posts_per_page' => - 1
		] );

		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				wp_set_post_terms(
					get_the_ID(),
					[ $term->term_id ],
					$taxonomy,
					true
				);
			}
		}
		wp_reset_postdata();

		wp_send_json_success( [
			'message' => 'Your request was successful.',
		] );

		exit();
	}
}
