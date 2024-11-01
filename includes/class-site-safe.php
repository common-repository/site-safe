<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://sitesafe-wp.com
 * @since      1.0.0
 *
 * @package    Site_Safe
 * @subpackage Site_Safe/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Site_Safe
 * @subpackage Site_Safe/includes
 * @author     https://sitesafe-wp.com <info@sitesafe-wp.com>
 */

require_once(__DIR__.'/functions.php');
 
class Site_Safe {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Site_Safe_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SITE_SAFE_VERSION' ) ) {
			$this->version = SITE_SAFE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'site-safe';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
	}
	
	private static $content_dir = null;
	
	public static function get_content_dir(){
		if(!empty(self::$content_dir)){
			return self::$content_dir;
		}
		$content_dir = plugin_dir_path(__FILE__).'/../../..';
		$content_dir = realpath($content_dir);
		self::$content_dir = $content_dir;
		return self::$content_dir;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Site_Safe_Loader. Orchestrates the hooks of the plugin.
	 * - Site_Safe_i18n. Defines internationalization functionality.
	 * - Site_Safe_Admin. Defines all hooks for the admin area.
	 * - Site_Safe_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-site-safe-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-site-safe-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-site-safe-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-site-safe-public.php';

		$this->loader = new Site_Safe_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Site_Safe_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Site_Safe_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Site_Safe_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		/////////////////////////////////////////////////////////////////////////////////////
		
		//////////////////////////
		// AJAX
		//////////////////////////
		$wpss_ajax = function(){
			global $wpdb;
			global $post;
			
			$ajax_fn = sanitize_text_field(@$_GET['fn']).'';
			if(!preg_match('/^[a-z0-9_-]+$/i', $ajax_fn)){
				die(json_encode(['error'=>'Function invalid.']));
			}
			if(!file_exists(__DIR__.'/ajax/'.$ajax_fn.'.php')){
				die(json_encode(['error'=>'Function not found']));
			}
			include(__DIR__.'/ajax/'.$ajax_fn.'.php');
		};
		
		add_action('wp_ajax_wpss', $wpss_ajax);
		add_action('wp_ajax_nopriv_wpss', $wpss_ajax);
		
		//////////////////////////
		
		
		$labels = array(
			'name' => _x('Files', 'plural'),
			'singular_name' => _x('File', 'singular'),
			'menu_name' => _x('Files', 'admin menu'),
			'name_admin_bar' => _x('Files', 'admin bar'),
			'add_new' => _x('Add New', 'add new'),
			'add_new_item' => __('Add New File'),
			'new_item' => __('New File'),
			'edit_item' => __('Edit File'),
			'view_item' => __('View Files'),
			'all_items' => __('All Files'),
			'search_items' => __('Search Files'),
			'not_found' => __('No files found.'),
		);
		
		
		// Hooking up our function to theme setup
		add_action('init', function(){
			register_taxonomy(
				'wpss-tag', //taxonomy 
				'wpss', //post-type
				array(
					'hierarchical'  => false, 
					'label'         => __( 'Tags','taxonomy general name'), 
					'singular_name' => __( 'Tag', 'taxonomy general name' ), 
					'rewrite'       => true, 
					'query_var'     => true 
				)
			);
			
			$pro = '';
			if(class_exists('Site_Safe_Pro')){
				$pro = ' Pro';
			}
			register_post_type('wpss',
				// CPT Options
				array(
					'labels' => array(
						'name' => __( 'Site Safe'.$pro ),
						'singular_name' => __( 'Site Safe'.$pro ),
						'add_new' => _x('Add New', 'add new'),
						'add_new_item' => __('Add New File'),
						'new_item' => __('New File'),
						'edit_item' => __('Edit File'),
						'view_item' => __('View Files'),
						'all_items' => __('All Files'),
						'search_items' => __('Search Files'),
						'not_found' => __('No files found.'),
					),
					'taxonomies' => array( 'category', 'wpss-tag' ),
					'public' => true,
					'has_archive' => true,
					'rewrite' => array('slug' => 'wpss'),
					//'show_in_rest' => true,
					'capability_type' => 'post',
					'supports' => array( 'title', 'editor', 'author', 'excerpt', 'thumbnail', 'comments', 'revisions', 'wp_custom_attachment', 'wpss_auto_private_url', 'wpss_ip_restrictions', 'wpss_access_rules', 'wpss_indexing_hotlinking_prevention' ),
				)
			);
		});
		
		
		add_action('save_post', function($post_id){
			global $post; 
			if ($post->post_type != 'wpss'){
				return;
			}
			
			if(!empty($_POST['wpss_file_id'])){
				update_post_meta($post->ID, 'wpss_file_id', sanitize_text_field($_POST['wpss_file_id']));
			}
		});

		function add_custom_meta_boxes() {
			// Define the custom attachment for posts
			add_meta_box(
				'wp_custom_attachment',
				'File',
				'wp_custom_attachment',
				'wpss',
				'side',
				'core'
			);

		} // end add_custom_meta_boxes
		add_action('add_meta_boxes', 'add_custom_meta_boxes');
		
		


		function wp_custom_attachment() {
			global $post;
			
			wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');
			
			if(!empty($post)){
				$wpss_file_id = get_post_meta($post->ID, 'wpss_file_id', true);

				$wpss_file_downloads = get_post_meta($post->ID, 'wpss_file_downloads', true);
				if(empty($wpss_file_downloads)){
					$wpss_file_downloads = 0;
				}

				$fileinfo = [];
				if(file_exists(Site_Safe::get_content_dir().'/wpss-downloads/'.$wpss_file_id.'.json')){
					$fileinfo = file_get_contents(Site_Safe::get_content_dir().'/wpss-downloads/'.$wpss_file_id.'.json');
					$fileinfo = json_decode($fileinfo, true);
				}
			}
			
			include(__DIR__.'/upload.widget.php');
			
		} // end wp_custom_attachment


		add_filter( 'the_content', 'filter_the_content_in_the_main_loop', 1 );
 
		function filter_the_content_in_the_main_loop( $content ) {
			global $post;
			if ( $post->post_type == 'wpss' ) {
				if ( file_exists( __DIR__ . '/template.php' ) ) {
					ob_start();
					include(__DIR__ . '/template.php');
					return ob_get_clean();
				}
			}
		 
			return $content;
		}
		
		

		add_action("admin_menu", function(){
			add_submenu_page('edit.php?post_type=wpss', 'Site Safe', 'Add-Ons', 0, 'wpss-addons', function(){
				if(empty($_GET['plugin-page'])){
					$_GET['plugin-page'] = 'index';
				}
				
				if(!preg_match('/^[a-z0-9_-]+$/i', $_GET['plugin-page'])){
					die('404');
				}
				
				$page_file = __DIR__.'/pages-addons/'.sanitize_text_field($_GET['plugin-page']).'.php';
				
				if(!file_exists($page_file)){
					die('404');
				}
				
				include($page_file);
			});
		});
		
		
		if(!class_exists('Site_Safe_Pro')){
			add_action('admin_menu', function() {
				global $submenu;

				$menu_slug = "edit.php?post_type=wpss";

				$submenu[$menu_slug][] = array('Buy Pro', 'manage_options', 'https://sitesafe-wp.com/product/site-safe-pro/');
			});
		}
		
		
		
		add_action("admin_menu", function(){
			add_submenu_page('edit.php?post_type=wpss', 'Site Safe', 'Settings', 0, 'wpss', function(){
				if(empty($_GET['plugin-page'])){
					$_GET['plugin-page'] = 'index';
				}
				
				if(!preg_match('/^[a-z0-9_-]+$/i', $_GET['plugin-page'])){
					die('404');
				}
				
				$page_file = __DIR__.'/pages/'.sanitize_text_field($_GET['plugin-page']).'.php';
				
				if(!file_exists($page_file)){
					die('404');
				}
				
				include($page_file);
			});
		});
		
		
		function add_meta_keys_to_revision( $keys ) {
			$keys[] = 'wpss_file_id';
			return $keys;
		}
		add_filter( 'wp_post_revision_meta_keys', 'add_meta_keys_to_revision' );
		
		/////////////////////////////////////////////////////////////////////////////////////
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Site_Safe_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		add_action( 'template_redirect', function(){
			global $post;
			global $wpdb;
			
			/////////////////////////////////////// BASIC ACCESS RULES
			if(!class_exists('Site_Safe_Pro')){
				if($post->post_type == 'wpss'){
					$deny = false;
					
					$current_user_id = get_current_user_id();
					
					$post_author_id = get_post_field('post_author', $post->ID);
					
					do{
						// ALWAYS ALLOW ADMINISTRATORS
						if(current_user_can('administrator')){
							break;
						}
						
						// ALWAYS ALLOW AUTHORS
						if($post_author_id == get_current_user_id()){
							break;
						}
						
						$deny = true;
					
					}while(false);
					
					if($deny){
						wp_die('Access Denied');
					}
				}
			}
			

			/////////////////////////////////////// DOWNLOAD
			if(!empty($_GET['wpss-download']) && preg_match('/^[a-zA-Z0-9-]+$/', $_GET['wpss-download'])){
				$wpss_file_id = sanitize_text_field(@$_GET['wpss-download']);
				
				$fileinfo = @file_get_contents(Site_Safe::get_content_dir().'/wpss-downloads/'.$wpss_file_id.'.json');
				$fileinfo = @json_decode($fileinfo, true);
				
				if(!empty($fileinfo)){
					$meta_ = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_postmeta WHERE meta_key='wpss_file_id' AND meta_value='%s' ", $wpss_file_id), ARRAY_A);
					
					$post_id = $meta_['post_id'];
					
					$wpss_file_downloads = get_post_meta($post_id, 'wpss_file_downloads', true);
					if(empty($wpss_file_downloads)){
						$wpss_file_downloads = 0;
					}
					
					update_post_meta($post_id, 'wpss_file_downloads', $wpss_file_downloads+1);
					
					$to = get_bloginfo('admin_email');
					
					$message = '
					
					The file was downloaded from IP '.$_SERVER['REMOTE_ADDR'].'
					
					';
					wp_mail($to, 'Site Safe Download', $message);
					
					header('Content-type: '.$fileinfo['type']);

					header('Content-Disposition: attachment; filename="'.$fileinfo['name'].'"');
					
					echo file_get_contents(Site_Safe::get_content_dir().'/wpss-downloads/'.$wpss_file_id);
					
					die;
				}
			}
			
			
		}, 10, 2 ); 
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Site_Safe_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
