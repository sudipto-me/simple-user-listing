<?php
/**
 * ShortCode class
 *
 * Shortcode class will go there
 */

defined( 'ABSPATH' ) || exit();

class User_Listing_ShortCode {
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_shortcode( 'simple-user-listing', array( __CLASS__, 'user_listing_shortcode_callback' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * Shortcode callback
	 *
	 * @param array $attrs   Arguments
	 * @param mixed $content Default NUll
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function user_listing_shortcode_callback( $attrs, $content = null ) {
		ob_start();
		$attrs       = shortcode_atts( array(), $attrs );
		$total_users = count( get_users() );
		if ( get_query_var( 'paged' ) ) {
			$page = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$page = get_query_var( 'page' );
		} else {
			$page = 1;
		}
		$user_per_page = 10;
		$total_pages   = 1;
		$offset        = $user_per_page * ( $page - 1 );
		$total_pages   = ceil( $total_users / $user_per_page );

		$args = array(
			'orderby' => 'display_name',
			'order'   => 'ASC',
			'number'  => $user_per_page,
			'offset'  => $offset
		);

		$user_query = new WP_User_Query( $args );
		$users      = $user_query->get_results();

		if ( $users ) {
			?>
            <table class="simple-user-listing-table">
                <thead>
                <tr>
                    <th class="user-email"><?php esc_html_e( 'User Email', 'user-listing' ) ?></th>
                    <th class="user-fname"><?php esc_html_e( 'First Name', 'user-listing' ) ?></th>
                    <th class="user-lname"><?php esc_html_e( 'Last Name', 'user-listing' ) ?></th>
                    <th class="user-action"><?php esc_html_e( 'Actions', 'user-listing' ) ?></th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ( $users as $user ) {
					$user_info = get_userdata( $user->ID );
					?>
                    <tr id="<?php echo $user->ID ?>" class="user-data">
                        <td><?php esc_html_e( $user_info->user_email ); ?></td>
                        <td><?php esc_html_e( $user_info->user_firstname ); ?></td>
                        <td><?php esc_html_e( $user_info->user_lastname ); ?></td>
                        <td>
                            <button class="user-actions"><?php esc_html_e( 'Hide', 'user-listing' ) ?></button>
                        </td>
                    </tr>
					<?php
				}
				?>
                </tbody>
            </table>
			<?php
		} else {
			echo '<h2>' . __( 'No User Found', 'user-listing' ) . '</h2>';
		}
		echo '<div class="user-pagination"><ul class="pagination"> <li>';
		echo paginate_links( array(
			'base'      => get_pagenum_link( 1 ) . '%_%',
			'format'    => 'page/%#%/',
			'prev_text' => __( '&laquo; Previous' ), // text for previous page
			'next_text' => __( 'Next &raquo;' ), // text for next page
			'total'     => $total_pages, // the total number of pages we have
			'current'   => $page, // the current page
			'end_size'  => 1,
			'mid_size'  => 5,
		) );
		echo '</li> </ul></div>';


		return ob_get_clean();
	}

	/**
	 * Enqueue scripts and style files
	 */
	public static function enqueue_scripts() {
		$js_path = user_listing()->plugin_url() . '/assets/js';
		wp_enqueue_script( 'user-listing-script', $js_path . '/frontend.js', array( 'jquery' ), USER_LISTING_PLUGIN_VERSION, true );
	}
}

new User_Listing_ShortCode();