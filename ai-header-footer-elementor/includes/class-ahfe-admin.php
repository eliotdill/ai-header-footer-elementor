<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AHFE_Admin {

	public static function init(): void {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
		add_action( 'admin_post_ahfe_set_template', [ __CLASS__, 'handle_set_template' ] );
		add_action( 'admin_post_ahfe_remove_template', [ __CLASS__, 'handle_remove_template' ] );
		add_action( 'admin_post_ahfe_upload_json', [ __CLASS__, 'handle_upload_json' ] );
	}

	public static function register_menu(): void {
		add_menu_page(
			__( 'AI Header & Footer', 'ai-hfe' ),
			__( 'AI Header & Footer', 'ai-hfe' ),
			'manage_options',
			'ai-header-footer',
			[ __CLASS__, 'render_landing_page' ],
			'dashicons-layout',
			60
		);

		add_submenu_page(
			'ai-header-footer',
			__( 'Template Manager', 'ai-hfe' ),
			__( 'Template Manager', 'ai-hfe' ),
			'manage_options',
			'ai-header-footer-manager',
			[ __CLASS__, 'render_manager_page' ]
		);
	}

	public static function enqueue_assets( string $hook ): void {
		if ( ! in_array( $hook, [ 'toplevel_page_ai-header-footer', 'ai-header-footer_page_ai-header-footer-manager' ], true ) ) {
			return;
		}
		wp_enqueue_style( 'ahfe-admin', AHFE_URL . 'assets/admin.css', [], AHFE_VERSION );
		wp_enqueue_script( 'ahfe-admin', AHFE_URL . 'assets/admin.js', [], AHFE_VERSION, true );
	}

	/* ------------------------------------------------------------------ */
	/* Landing Page                                                         */
	/* ------------------------------------------------------------------ */

	public static function render_landing_page(): void {
		$manager_url    = admin_url( 'admin.php?page=ai-header-footer-manager' );
		$pressmegpt_url = 'https://pressmegpt.com';
		?>
		<div class="ahfe-wrap">

			<div class="ahfe-logo-bar">
				<span class="ahfe-logo-text">AI Header &amp; Footer <span class="ahfe-logo-by">by PressMeGPT</span></span>
			</div>

			<div class="ahfe-landing-cards">

				<!-- Card 1: Plugin functionality -->
				<div class="ahfe-card ahfe-card--primary">
					<div class="ahfe-card-badge ahfe-card-badge--free"><?php esc_html_e( 'FREE – No Account Required', 'ai-hfe' ); ?></div>
					<div class="ahfe-card-icon dashicons dashicons-layout"></div>
					<h2 class="ahfe-card-title"><?php esc_html_e( 'Set a Header or Footer for Elementor', 'ai-hfe' ); ?></h2>
					<p class="ahfe-card-desc"><?php esc_html_e( 'Upload or choose an Elementor template and set it as your site\'s global header or footer — no coding required.', 'ai-hfe' ); ?></p>
					<a href="<?php echo esc_url( $manager_url ); ?>" class="ahfe-btn ahfe-btn--primary">
						<?php esc_html_e( 'Get Started', 'ai-hfe' ); ?> &rarr;
					</a>
				</div>

				<!-- Card 2: Convert site -->
				<div class="ahfe-card">
					<div class="ahfe-card-badge"><?php esc_html_e( 'FREE & PAID – Account Required', 'ai-hfe' ); ?></div>
					<div class="ahfe-card-icon dashicons dashicons-art"></div>
					<h2 class="ahfe-card-title"><?php esc_html_e( 'Convert a Site to a WordPress Theme with AI', 'ai-hfe' ); ?></h2>
					<p class="ahfe-card-desc"><?php esc_html_e( 'Turn any existing website design into a fully editable WordPress theme in minutes using AI.', 'ai-hfe' ); ?></p>
					<a href="<?php echo esc_url( $pressmegpt_url ); ?>" class="ahfe-btn ahfe-btn--outline" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Go to PressMeGPT', 'ai-hfe' ); ?> &rarr;
					</a>
				</div>

				<!-- Card 3: Create theme -->
				<div class="ahfe-card">
					<div class="ahfe-card-badge"><?php esc_html_e( 'FREE & PAID – Account Required', 'ai-hfe' ); ?></div>
					<div class="ahfe-card-icon dashicons dashicons-admin-customizer"></div>
					<h2 class="ahfe-card-title"><?php esc_html_e( 'Create a Theme with AI', 'ai-hfe' ); ?></h2>
					<p class="ahfe-card-desc"><?php esc_html_e( 'Design a custom WordPress theme from scratch using AI — no coding needed. Own your theme and host anywhere.', 'ai-hfe' ); ?></p>
					<a href="<?php echo esc_url( $pressmegpt_url ); ?>" class="ahfe-btn ahfe-btn--outline" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Go to PressMeGPT', 'ai-hfe' ); ?> &rarr;
					</a>
				</div>

			</div><!-- .ahfe-landing-cards -->

			<?php self::render_footer_tagline(); ?>

		</div><!-- .ahfe-wrap -->
		<?php
	}

	/* ------------------------------------------------------------------ */
	/* Template Manager Page                                                */
	/* ------------------------------------------------------------------ */

	public static function render_manager_page(): void {
		$types      = AHFE_Content_Types::get_all();
		$back_url   = admin_url( 'admin.php?page=ai-header-footer' );
		$all_templates = self::get_elementor_templates();
		?>
		<div class="ahfe-wrap">

			<div class="ahfe-logo-bar">
				<span class="ahfe-logo-text">AI Header &amp; Footer <span class="ahfe-logo-by">by PressMeGPT</span></span>
				<a href="<?php echo esc_url( $back_url ); ?>" class="ahfe-back-link">&larr; <?php esc_html_e( 'Back to Home', 'ai-hfe' ); ?></a>
			</div>

			<h1 class="ahfe-page-title"><?php esc_html_e( 'Template Manager', 'ai-hfe' ); ?></h1>

			<?php if ( isset( $_GET['ahfe_notice'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
				<?php self::render_notice( sanitize_text_field( wp_unslash( $_GET['ahfe_notice'] ) ) ); ?>
			<?php endif; ?>

			<?php foreach ( $types as $type ) : ?>
				<?php self::render_type_section( $type, $all_templates ); ?>
			<?php endforeach; ?>

			<?php self::render_footer_tagline(); ?>

		</div><!-- .ahfe-wrap -->
		<?php
	}

	private static function render_type_section( array $type, array $all_templates ): void {
		$type_id     = $type['id'];
		$template_id = (int) get_option( $type['option_key'], 0 );
		$has_template = $template_id > 0 && get_post( $template_id );

		$template_title = $has_template ? get_the_title( $template_id ) : '';
		$edit_url       = $has_template ? admin_url( "post.php?post={$template_id}&action=elementor" ) : '';
		?>
		<div class="ahfe-type-section">
			<div class="ahfe-type-header">
				<h2 class="ahfe-type-title"><?php echo esc_html( $type['label'] ); ?></h2>
				<p class="ahfe-type-desc"><?php echo esc_html( $type['description'] ); ?></p>
			</div>

			<div class="ahfe-type-body">

				<!-- Current status -->
				<div class="ahfe-status <?php echo $has_template ? 'ahfe-status--active' : 'ahfe-status--none'; ?>">
					<?php if ( $has_template ) : ?>
						<span class="ahfe-status-dot"></span>
						<span class="ahfe-status-label">
							<?php
							printf(
								/* translators: %s: template name */
								esc_html__( 'Active: %s', 'ai-hfe' ),
								'<strong>' . esc_html( $template_title ) . '</strong>'
							);
							?>
						</span>
						<div class="ahfe-status-actions">
							<a href="<?php echo esc_url( $edit_url ); ?>" class="ahfe-btn ahfe-btn--sm ahfe-btn--outline" target="_blank">
								&#9998; <?php esc_html_e( 'Edit with Elementor', 'ai-hfe' ); ?>
							</a>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;">
								<?php wp_nonce_field( 'ahfe_remove_' . $type_id, 'ahfe_nonce' ); ?>
								<input type="hidden" name="action" value="ahfe_remove_template">
								<input type="hidden" name="ahfe_type_id" value="<?php echo esc_attr( $type_id ); ?>">
								<button type="submit" class="ahfe-btn ahfe-btn--sm ahfe-btn--danger">
									&#215; <?php esc_html_e( 'Remove', 'ai-hfe' ); ?>
								</button>
							</form>
						</div>
					<?php else : ?>
						<span class="ahfe-status-dot ahfe-status-dot--off"></span>
						<span class="ahfe-status-label"><?php esc_html_e( 'No template set', 'ai-hfe' ); ?></span>
					<?php endif; ?>
				</div>

				<!-- Tabs: Browse / Upload -->
				<div class="ahfe-tabs" data-type="<?php echo esc_attr( $type_id ); ?>">
					<div class="ahfe-tab-nav">
						<button class="ahfe-tab-btn ahfe-tab-btn--active" data-tab="browse-<?php echo esc_attr( $type_id ); ?>">
							<?php esc_html_e( 'Browse Templates', 'ai-hfe' ); ?>
						</button>
						<button class="ahfe-tab-btn" data-tab="upload-<?php echo esc_attr( $type_id ); ?>">
							<?php esc_html_e( 'Upload JSON', 'ai-hfe' ); ?>
						</button>
					</div>

					<!-- Tab: Browse -->
					<div class="ahfe-tab-panel ahfe-tab-panel--active" id="browse-<?php echo esc_attr( $type_id ); ?>">
						<?php if ( empty( $all_templates ) ) : ?>
							<p class="ahfe-notice-inline">
								<?php esc_html_e( 'No Elementor templates found. Create one in Elementor → My Templates, or upload a JSON file below.', 'ai-hfe' ); ?>
							</p>
						<?php else : ?>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="ahfe-form-inline">
								<?php wp_nonce_field( 'ahfe_set_' . $type_id, 'ahfe_nonce' ); ?>
								<input type="hidden" name="action" value="ahfe_set_template">
								<input type="hidden" name="ahfe_type_id" value="<?php echo esc_attr( $type_id ); ?>">
								<select name="ahfe_template_id" class="ahfe-select">
									<option value=""><?php esc_html_e( '— Select a template —', 'ai-hfe' ); ?></option>
									<?php foreach ( $all_templates as $tpl ) : ?>
										<option value="<?php echo esc_attr( $tpl->ID ); ?>" <?php selected( $tpl->ID, $template_id ); ?>>
											<?php echo esc_html( $tpl->post_title ); ?>
										</option>
									<?php endforeach; ?>
								</select>
								<button type="submit" class="ahfe-btn ahfe-btn--primary">
									<?php
									printf(
										/* translators: %s: content type label */
										esc_html__( 'Set as %s', 'ai-hfe' ),
										esc_html( $type['label'] )
									);
									?>
								</button>
							</form>
						<?php endif; ?>
					</div>

					<!-- Tab: Upload -->
					<div class="ahfe-tab-panel" id="upload-<?php echo esc_attr( $type_id ); ?>">
						<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data" class="ahfe-form-inline">
							<?php wp_nonce_field( 'ahfe_upload_' . $type_id, 'ahfe_nonce' ); ?>
							<input type="hidden" name="action" value="ahfe_upload_json">
							<input type="hidden" name="ahfe_type_id" value="<?php echo esc_attr( $type_id ); ?>">
							<label class="ahfe-file-label">
								<input type="file" name="ahfe_json_file" accept=".json" class="ahfe-file-input">
								<span class="ahfe-file-text"><?php esc_html_e( 'Choose Elementor JSON file…', 'ai-hfe' ); ?></span>
							</label>
							<button type="submit" class="ahfe-btn ahfe-btn--primary">
								<?php esc_html_e( 'Upload &amp; Set', 'ai-hfe' ); ?>
							</button>
						</form>
					</div>

				</div><!-- .ahfe-tabs -->

			</div><!-- .ahfe-type-body -->
		</div><!-- .ahfe-type-section -->
		<?php
	}

	/* ------------------------------------------------------------------ */
	/* Form Handlers                                                        */
	/* ------------------------------------------------------------------ */

	public static function handle_set_template(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Permission denied.', 'ai-hfe' ) );
		}

		$type_id = isset( $_POST['ahfe_type_id'] ) ? sanitize_key( $_POST['ahfe_type_id'] ) : '';
		check_admin_referer( 'ahfe_set_' . $type_id, 'ahfe_nonce' );

		$type = AHFE_Content_Types::get( $type_id );
		if ( ! $type ) {
			wp_die( esc_html__( 'Invalid content type.', 'ai-hfe' ) );
		}

		$template_id = isset( $_POST['ahfe_template_id'] ) ? (int) $_POST['ahfe_template_id'] : 0;
		if ( $template_id > 0 ) {
			update_option( $type['option_key'], $template_id );
			$notice = 'set_ok';
		} else {
			$notice = 'no_template';
		}

		wp_safe_redirect( add_query_arg( 'ahfe_notice', $notice, admin_url( 'admin.php?page=ai-header-footer-manager' ) ) );
		exit;
	}

	public static function handle_remove_template(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Permission denied.', 'ai-hfe' ) );
		}

		$type_id = isset( $_POST['ahfe_type_id'] ) ? sanitize_key( $_POST['ahfe_type_id'] ) : '';
		check_admin_referer( 'ahfe_remove_' . $type_id, 'ahfe_nonce' );

		$type = AHFE_Content_Types::get( $type_id );
		if ( ! $type ) {
			wp_die( esc_html__( 'Invalid content type.', 'ai-hfe' ) );
		}

		delete_option( $type['option_key'] );

		wp_safe_redirect( add_query_arg( 'ahfe_notice', 'removed', admin_url( 'admin.php?page=ai-header-footer-manager' ) ) );
		exit;
	}

	public static function handle_upload_json(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Permission denied.', 'ai-hfe' ) );
		}

		$type_id = isset( $_POST['ahfe_type_id'] ) ? sanitize_key( $_POST['ahfe_type_id'] ) : '';
		check_admin_referer( 'ahfe_upload_' . $type_id, 'ahfe_nonce' );

		$type = AHFE_Content_Types::get( $type_id );
		if ( ! $type ) {
			wp_die( esc_html__( 'Invalid content type.', 'ai-hfe' ) );
		}

		if ( empty( $_FILES['ahfe_json_file']['tmp_name'] ) ) {
			wp_safe_redirect( add_query_arg( 'ahfe_notice', 'no_file', admin_url( 'admin.php?page=ai-header-footer-manager' ) ) );
			exit;
		}

		// Validate file type via extension (MIME sniffing for JSON is unreliable).
		$filename = sanitize_file_name( $_FILES['ahfe_json_file']['name'] );
		if ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) !== 'json' ) {
			wp_safe_redirect( add_query_arg( 'ahfe_notice', 'bad_file', admin_url( 'admin.php?page=ai-header-footer-manager' ) ) );
			exit;
		}

		$tmp_path = $_FILES['ahfe_json_file']['tmp_name'];
		$post_id  = AHFE_Importer::import_json( $tmp_path, $type_id );

		if ( is_wp_error( $post_id ) ) {
			wp_safe_redirect( add_query_arg( 'ahfe_notice', 'import_error', admin_url( 'admin.php?page=ai-header-footer-manager' ) ) );
			exit;
		}

		update_option( $type['option_key'], $post_id );

		wp_safe_redirect( add_query_arg( 'ahfe_notice', 'uploaded_ok', admin_url( 'admin.php?page=ai-header-footer-manager' ) ) );
		exit;
	}

	/* ------------------------------------------------------------------ */
	/* Helpers                                                              */
	/* ------------------------------------------------------------------ */

	private static function get_elementor_templates(): array {
		return get_posts( [
			'post_type'      => 'elementor_library',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		] );
	}

	private static function render_notice( string $code ): void {
		$messages = [
			'set_ok'       => [ 'success', __( 'Template set successfully.', 'ai-hfe' ) ],
			'removed'      => [ 'success', __( 'Template removed. Your theme default is restored.', 'ai-hfe' ) ],
			'uploaded_ok'  => [ 'success', __( 'JSON imported and template set successfully.', 'ai-hfe' ) ],
			'no_template'  => [ 'warning', __( 'Please select a template.', 'ai-hfe' ) ],
			'no_file'      => [ 'warning', __( 'Please choose a JSON file to upload.', 'ai-hfe' ) ],
			'bad_file'     => [ 'error',   __( 'Only .json files are accepted.', 'ai-hfe' ) ],
			'import_error' => [ 'error',   __( 'Import failed. Please check that the file is a valid Elementor JSON export.', 'ai-hfe' ) ],
		];

		if ( ! isset( $messages[ $code ] ) ) {
			return;
		}

		[ $type, $message ] = $messages[ $code ];
		?>
		<div class="ahfe-admin-notice ahfe-admin-notice--<?php echo esc_attr( $type ); ?>">
			<?php echo esc_html( $message ); ?>
		</div>
		<?php
	}

	private static function render_footer_tagline(): void {
		?>
		<div class="ahfe-footer-tagline">
			<a href="https://pressmegpt.com" target="_blank" rel="noopener noreferrer">PressMeGPT.com</a>
			<?php esc_html_e( '– Use AI to Design or Convert a Website to a WordPress Theme in Minutes. No Lock-in. Own Your Theme. Host Anywhere.', 'ai-hfe' ); ?>
		</div>
		<?php
	}
}
