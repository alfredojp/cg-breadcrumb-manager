<?php
/**
 * CG Archive Text
 *
 * @package     Capgemini_Breadcrumb_Manager
 * @author      Capgemini GIT
 * @copyright   2021 Capgemini
 * @license     GPLv2
 *
 * @wordpress-plugin
 * Plugin Name:    Capgemini Breadcrumb Manager
 * Plugin URI:        https://github.com/wpcomvip/capgemini
 * Description:       Add options to manage breadcrumb
 * Version:           0.1.0
 * Author:            Capgemini GIT
 * Text Domain:       cg-breadcrumb-manager
 * License:           GPL-2.0-or-later
 */

function breadcrumb_manager_admin_menu() {
  add_menu_page(
      __( 'Breadcrumb Manager', 'cg-breadcrumb-manager-admin' ),
      __( 'Breadcrumb Manager', 'cg-breadcrumb-manager-admin' ),
      'manage_options',
      'breadcrumb-manager',
      'breadcrumb_manager_page_contents',
      'dashicons-schedule',
      30
  );
}
add_action( 'admin_menu', 'breadcrumb_manager_admin_menu' );

add_action( 'plugins_loaded', function() {
  load_plugin_textdomain( 'cg-breadcrumb-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});

function enqueue_breadcrumb_assets() {
  wp_register_script( 'breadcrumb-settings-js', plugins_url( 'cg-breadcrumb-manager/assets/js/breadcrumb-settings.js' ), [ 'wp-i18n' ], '1.1', false );

  wp_enqueue_script('breadcrumb-settings-js');

  wp_set_script_translations( 'breadcrumb-settings-js', 'cg-breadcrumb-manager', plugin_dir_path(__FILE__) . 'languages/' );

  $menu_items_arr = [];
  $main_menu_items = wp_get_nav_menu_items('main-menu');
  foreach ($main_menu_items as $key => $value) {
    $menu_items_arr[$value->menu_item_parent][$value->ID] = $value->title;
  }

  $pathName = '';
	$currentSiteDetails = get_blog_details();
	if ( isset($currentSiteDetails->path) ) {
	  $pathName = str_replace("/", "", $currentSiteDetails->path);
	}

  wp_localize_script('cg-blocks-js', 'breadcrumb_manager_var', array(
       'menu_items_arr' => wp_json_encode( $menu_items_arr ),
       'pathName' => $pathName,
   ));

  wp_enqueue_style( 'breadcrumb-settings-css', plugins_url( 'cg-breadcrumb-manager/assets/css/breadcrumb-settings.css' ),
		[], '1.0', false );
}
add_action( 'admin_enqueue_scripts', 'enqueue_breadcrumb_assets' );

function breadcrumb_manager_page_contents() {
  ?>
  <form method="POST" action="options.php" id="breadcrumb_manager_form">
    <?php
      settings_fields( 'breadcrumb-manager' );
      do_settings_sections( 'breadcrumb-manager' );
    ?>
  </form>
  <?php
}


add_action( 'admin_init', 'breadcrumb_manager_settings_init' );

function breadcrumb_manager_settings_init() {

  add_settings_section(
      'breadcrumb_manager_setting_section',
      __( 'Breadcrumb Manager', 'capgemini' ),
      'breadcrumb_manager_setting_section_callback_function',
      'breadcrumb-manager'
  );

	add_settings_field(
	   'breadcrumb_manager_setting_field',
	   '<span style="margin:0;margin-left:10px;font-size:18px;" id="breadcrumb_section_label">' . __( 'Add new setting', 'cg-breadcrumb-manager-admin' ) . '</span>',
	   'breadcrumb_manager_setting_markup',
	   'breadcrumb-manager',
	   'breadcrumb_manager_setting_section'
	);

	register_setting( 'breadcrumb-manager', 'breadcrumb_manager_setting_field' );
}


function breadcrumb_manager_setting_section_callback_function() {
  //echo '<p>Intro text for our settings section</p>';
}

function breadcrumb_manager_setting_markup() {

  ?>
    </tr>
  </table>
  <table class="form-table form-table-body" id="breadcrumb_manager_setting_body">
    <tr>
      <td colspan="3"><div class="section-head"><?php esc_html_e( 'Content Section', 'cg-breadcrumb-manager-admin' ) ?>:</div></td>
    </tr>
    <tr class="content-section-row">
      <td width="33.33%">
        <label for="choose-post-type"><?php esc_html_e( 'Choose Post Type', 'cg-breadcrumb-manager-admin' ); ?></label><br>
        <select id="breadcrumb_manager_setting_field_post_type" class="breadcrumb_manager_content_dropdowns">
          <option value=""><?php esc_html_e( 'Select post type', 'cg-breadcrumb-manager-admin' ) ?></option>
          <option value="post"><?php esc_html_e( 'Blog post', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="event"><?php esc_html_e( 'Event', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="press-release"><?php esc_html_e( 'News', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="people"><?php esc_html_e( 'People', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="employee-testimonial"><?php esc_html_e( 'Employee testimonial', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="story"><?php esc_html_e( 'Story', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="location"><?php esc_html_e( 'Location', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="client-story"><?php esc_html_e( 'Client story', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="research-and-insight"><?php esc_html_e( 'Research and insight', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="resource"><?php esc_html_e( 'Resource', 'cg-breadcrumb-manager-admin' ); ?></option>
          <option value="analyst-report"><?php esc_html_e( 'Analyst report', 'cg-breadcrumb-manager-admin' ); ?></option>
        </select>
      </td>
      <td width="33.33%">
        <label for="choose-post-type-taxonomy"><?php esc_html_e( 'Choose Taxonomy', 'cg-breadcrumb-manager-admin' ); ?></label><br>
        <select id="breadcrumb_manager_setting_field_taxonomy" class="breadcrumb_manager_content_dropdowns">
          <option value=""><?php esc_html_e( 'Select Taxonomy', 'cg-breadcrumb-manager-admin' ); ?></option>
        </select>
      </td>
      <td width="33.33%">
        <label for="choose-post-type-taxonomy-term"><?php esc_html_e( 'Choose Term', 'cg-breadcrumb-manager-admin' ); ?></label><br>
        <select id="breadcrumb_manager_setting_field_taxonomy_term" class="breadcrumb_manager_content_dropdowns">
          <option value=""><?php esc_html_e( 'Select Term', 'cg-breadcrumb-manager-admin' ); ?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="3"><div class="section-head"><?php esc_html_e( 'Menu Section', 'cg-breadcrumb-manager-admin' ); ?>:</div></td>
    </tr>
    <tr>
      <td colspan="3">
        <table class="inner-table">
          <tr>
            <td>
              <div class="menu-section">
                <div class="menu-section-items">
                  <label for="choose-menu-section"><?php esc_html_e( 'Choose Menu Section', 'cg-breadcrumb-manager-admin' ); ?></label><br>
                  <select id="breadcrumb_manager_setting_field_menu_section_0" onchange="get_menu_item_dropdown(this.value, 0)">
                    <option value=""><?php esc_html_e( 'Select Menu', 'cg-breadcrumb-manager-admin' ) ?></option>
                  </select>
                </div>
              </div><br clear="all" />
              <div class="current-menu-position">
                <div><?php esc_html_e( 'Current Menu Position', 'cg-breadcrumb-manager-admin' ) ?>: <span id="position_breadcrumb"></span></div>
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>


  <table>
    <tr>
      <td>
        <input type="hidden" id="breadcrumb_manager_setting_field" name="breadcrumb_manager_setting_field" value="<?php echo esc_attr( get_option( 'breadcrumb_manager_setting_field' ) ); ?>">
        <input type="hidden" id="breadcrumb_manager_setting_field_delete_id" name="breadcrumb_manager_setting_field_delete_id" value="" />
        <input type="hidden" id="breadcrumb_manager_setting_field_edit_id" name="breadcrumb_manager_setting_field_edit_id" value="" />
        <?php submit_button(); ?>
      </td>
    </tr>
  </table>

  <table>
    <tr><td><h4><?php esc_html_e( 'Settings List', 'cg-breadcrumb-manager-admin' ) ?></h4></td></tr>
  </table>

  <?php

  $allPostTypes = [
	'post'  => __( 'Blog post', 'cg-breadcrumb-manager-admin' ),
    'people'  => __( 'People', 'cg-breadcrumb-manager-admin' ),
    'story'   => __( 'Story', 'cg-breadcrumb-manager-admin' ),
    'press-release' => __( 'News', 'cg-breadcrumb-manager-admin' ),
    'employee-testimonial' => __( 'Employee Testimonial', 'cg-breadcrumb-manager-admin' ),
    'event' => __( 'Event', 'cg-breadcrumb-manager-admin' ),
    'location' => __( 'Location', 'cg-breadcrumb-manager-admin' ),
    'client-story' => __( 'Client Story', 'cg-breadcrumb-manager-admin' ),
    'research-and-insight' => __( 'Research and insight', 'cg-breadcrumb-manager-admin' ),
    'resource' => __( 'Resource', 'cg-breadcrumb-manager-admin' ),
    'analyst-report' => __( 'Analyst report', 'cg-breadcrumb-manager-admin' ),
  ];

  $allTaxonomies = [
	'post'  => [
      'blog-topic' => __( 'Blog topic', 'cg-breadcrumb-manager-admin' ),
      'brand'       => __( 'Brand', 'cg-breadcrumb-manager-admin' ),
      'service'     => __( 'Service', 'cg-breadcrumb-manager-admin' ),
      'industry'    => __( 'Industry', 'cg-breadcrumb-manager-admin' ),
      'partners'    => __( 'Partner', 'cg-breadcrumb-manager-admin' ),
    ],
    'event'   => [
      'brand'       => __( 'Brand', 'cg-breadcrumb-manager-admin' ),
    ],
    'press-release'   => [
      'press-release-type'  => __( 'People Type', 'cg-breadcrumb-manager-admin' ),
      'brand'               => __( 'Brand', 'cg-breadcrumb-manager-admin' ),
    ],
    'people'  => [
      'people-type' => __( 'People Type', 'cg-breadcrumb-manager-admin' ),
      'brand'       => __( 'Brand', 'cg-breadcrumb-manager-admin' ),
      'service'     => __( 'Service', 'cg-breadcrumb-manager-admin' ),
      'industry'    => __( 'Industry', 'cg-breadcrumb-manager-admin' ),
      'partners'    => __( 'Partner', 'cg-breadcrumb-manager-admin' ),
    ],
    'employee-testimonial'  => [
      'brand'       => __( 'Brand', 'cg-breadcrumb-manager-admin' ),
      'country'     => __( 'Country', 'cg-breadcrumb-manager-admin' ),
      'grade'       => __( 'Grade', 'cg-breadcrumb-manager-admin' ),
      'job_family'  => __( 'Job Famliy', 'cg-breadcrumb-manager-admin' ),
    ],
    'story'   => [
      'story-theme' => __( 'Story Theme', 'cg-breadcrumb-manager-admin' ),
    ],
    'location'  => [],
    'client-story'  => [
      'brand'       => __( 'Brand', 'cg-breadcrumb-manager-admin' ),
      'service'     => __( 'Service', 'cg-breadcrumb-manager-admin' ),
      'industry'    => __( 'Industry', 'cg-breadcrumb-manager-admin' ),
      'partners'    => __( 'Partner', 'cg-breadcrumb-manager-admin' ),
      'country'     => __( 'Country', 'cg-breadcrumb-manager-admin' ),
    ],
    'research-and-insight'  => [
      'research-and-insight-type' => __( 'Research & Insight Type', 'cg-breadcrumb-manager-admin' ),
      'theme'                     => __( 'Theme', 'cg-breadcrumb-manager-admin' ),
      'brand'                     => __( 'Brand', 'cg-breadcrumb-manager-admin' ),
      'service'                   => __( 'Service', 'cg-breadcrumb-manager-admin' ),
      'industry'                  => __( 'Industry', 'cg-breadcrumb-manager-admin' ),
      'partners'                  => __( 'Partner', 'cg-breadcrumb-manager-admin' ),
    ],
    'resource'  => [
      'resource-type' => __( 'Resource Type', 'cg-breadcrumb-manager-admin' ),
      'brand'         => __( 'Brand', 'cg-breadcrumb-manager-admin' ),
      'service'       => __( 'Service', 'cg-breadcrumb-manager-admin' ),
      'industry'      => __( 'Industry', 'cg-breadcrumb-manager-admin' ),
      'partners'      => __( 'Partner', 'cg-breadcrumb-manager-admin' ),
    ],
    'analyst-report'  => [
      'analyst'     => __( 'Analyst', 'cg-breadcrumb-manager-admin' ),
      'service'     => __( 'Service', 'cg-breadcrumb-manager-admin' ),
      'industry'    => __( 'Industry', 'cg-breadcrumb-manager-admin' ),
      'partners'    => __( 'Partner', 'cg-breadcrumb-manager-admin' ),
    ],
  ];

  $allTerms = [];

  $all_brands = get_terms( array(
		'taxonomy' => 'brand',
		'hide_empty' => false,
	) );

  foreach ($all_brands as $key => $value) {
    $allTerms['brand'][$value->term_id] = $value->name;
  }

  $all_people_types = get_terms( array(
		'taxonomy' => 'people-type',
		'hide_empty' => false,
	) );

  foreach ($all_people_types as $key => $value) {
    $allTerms['people-type'][$value->term_id] = $value->name;
  }

  $all_services = get_terms( array(
		'taxonomy' => 'service',
		'hide_empty' => false,
	) );

  foreach ($all_services as $key => $value) {
    $allTerms['service'][$value->term_id] = $value->name;
  }

  $all_industry = get_terms( array(
		'taxonomy' => 'industry',
		'hide_empty' => false,
	) );

  foreach ($all_industry as $key => $value) {
    $allTerms['industry'][$value->term_id] = $value->name;
  }

  $all_partners = get_terms( array(
		'taxonomy' => 'partners',
		'hide_empty' => false,
	) );

  foreach ($all_partners as $key => $value) {
    $allTerms['partners'][$value->term_id] = $value->name;
  }

  $all_story_themes = get_terms( array(
		'taxonomy' => 'story-theme',
		'hide_empty' => false,
	) );

  foreach ($all_story_themes as $key => $value) {
    $allTerms['story-theme'][$value->term_id] = $value->name;
  }

  $option = get_option( 'breadcrumb_manager_setting_field' );
  if ( $option !== '' && $option !== null && $option !== false ) {
    $optionArr = json_decode( $option );
    if ( count( $optionArr ) ) {
  ?>

      <table class="wp-list-table widefat fixed striped table-view-list posts" style="width:98%">
        <thead>
          <tr>
            <th scope="col" class="manage-column column-post-type"><?php esc_html_e( 'Post type', 'cg-breadcrumb-manager-admin' ) ?></th>
            <th scope="col" class="manage-column column-taxonomy"><?php esc_html_e( 'Taxonomy', 'cg-breadcrumb-manager-admin' ) ?></th>
            <th scope="col" class="manage-column column-term"><?php esc_html_e( 'Term', 'cg-breadcrumb-manager-admin' ) ?></th>
            <th scope="col" class="manage-column column-menu-section"><?php esc_html_e( 'Menu section', 'cg-breadcrumb-manager-admin' ) ?></th>
          </tr>
        </thead>

        <tbody>

        <?php

        foreach ($optionArr as $key => $value) {
          $postTypeVal = $value->post_type;
          $taxonomyVal = $value->taxonomy;
          $termVal = $value->term;
          $menuItemIdVal = $value->menu_item_id;
          $positionStrVal = $value->position_str;
          ?>

          <tr id="breadcrumb_manager_setting_field_<?php echo esc_attr( $key ) ?>">
            <td>
              <?php echo esc_html( $allPostTypes[ $postTypeVal ] ) ?>
              <input type="hidden" name="breadcrumb_manager_setting_field_post_type_<?php echo esc_attr( $key ) ?>" id="breadcrumb_manager_setting_field_post_type_<?php echo esc_attr( $key ) ?>" value="<?php echo esc_attr( $postTypeVal ) ?>" />
              <div class="row-actions">
                <span class="edit">
                  <a href="javascript:void(0);" onclick="populate_breadcrumb_setting_field(<?php echo esc_attr( $key ) ?>)" aria-label="Edit “<?php echo esc_attr( $allPostTypes[ $postTypeVal ] ) ?>”"><?php esc_html_e( 'Edit', 'cg-breadcrumb-manager-admin' ); ?></a> | </span><span class="trash"><a href="javascript:void(0);" onclick="delete_breadcrumb_setting_field(<?php echo esc_attr( $key ) ?>)" class="submitdelete" aria-label="Delete “<?php echo esc_attr( $allPostTypes[ $postTypeVal ] ) ?>” "><?php esc_html_e( 'Delete', 'cg-breadcrumb-manager-admin' ); ?></a> </span></div>
            </td>
            <td>
              <?php echo isset( $allTaxonomies[ $postTypeVal ][ $taxonomyVal ] ) ? esc_html( $allTaxonomies[ $postTypeVal ][ $taxonomyVal ] ) : '-' ?>
              <input type="hidden" name="breadcrumb_manager_setting_field_taxonomy_<?php echo esc_attr( $key ) ?>" id="breadcrumb_manager_setting_field_taxonomy_<?php echo esc_attr( $key ) ?>" value="<?php echo esc_attr( $taxonomyVal ) ?>" />
            </td>
            <td>
              <?php echo isset( $allTerms[ $taxonomyVal ][ $termVal ] ) ? esc_html( $allTerms[ $taxonomyVal ][ $termVal ] ) : '-' ?>
              <input type="hidden" name="breadcrumb_manager_setting_field_taxonomy_term_<?php echo esc_attr( $key ) ?>" id="breadcrumb_manager_setting_field_taxonomy_term_<?php echo esc_attr( $key ) ?>" value="<?php echo esc_attr( $termVal ) ?>" />
            </td>
            <td>
              <?php echo esc_html( $positionStrVal ) ?>
              <input type="hidden" name="breadcrumb_manager_setting_field_menu_item_id_<?php echo esc_attr( $key ) ?>" id="breadcrumb_manager_setting_field_menu_item_id_<?php echo esc_attr( $key ) ?>" value="<?php echo esc_attr( $menuItemIdVal ) ?>" />
              <input type="hidden" name="breadcrumb_manager_setting_field_position_str_<?php echo esc_attr( $key ) ?>" id="breadcrumb_manager_setting_field_position_str_<?php echo esc_attr( $key ) ?>" value="<?php echo esc_attr( $positionStrVal ) ?>" />
            </td>
          </tr>

          <?php

        }
        ?>

        </tbody>

      </table>

    <?php
    } else {
      ?>

      <div class="warning-message"><?php esc_html_e( 'No settings added yet!', 'cg-breadcrumb-manager-admin' ); ?></div>

      <?php
    }
  } else {
    ?>

    <div class="warning-message"><?php esc_html_e( 'No settings added yet!', 'cg-breadcrumb-manager-admin' ); ?></div>

    <?php
  }
}
