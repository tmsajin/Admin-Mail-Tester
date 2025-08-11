<?php
/**
 * Plugin Name:       Admin Mail Tester
 * Plugin URI:        https://github.com/tmsajin/WP-Admin-Mail-Tester
 * Description:       Send a test email with an attachment from the WordPress admin to verify email functionality.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sajin TM
 * Author URI:        https://www.linkedin.com/in/tmsajin/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       admin-mail-tester
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Add a menu item in the admin dashboard.
 */
add_action('admin_menu', function() {
    add_management_page(
        __('Mail Tester', 'admin-mail-tester'),
        __('Mail Tester', 'admin-mail-tester'),
        'manage_options',
        'admin-mail-tester',
        'wpamt_mail_tester_page'
    );
});

/**
 * Render the mail tester page in the admin.
 */
function wpamt_mail_tester_page() {
    $sent = false;
    $error = '';
    $attachment_path = '';

    // Check if the form has been submitted.
    if ( isset( $_POST['wpamt_send_test_mail'], $_POST['wpamt_mail_tester_nonce'] ) ) {
        // Verify the nonce for security.
        if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpamt_mail_tester_nonce'] ) ), 'wpamt_mail_tester_action' ) ) {
            
            $to = isset( $_POST['wpamt_to'] ) ? sanitize_email( wp_unslash( $_POST['wpamt_to'] ) ) : '';
            $subject = isset( $_POST['wpamt_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['wpamt_subject'] ) ) : '';
            $message = isset( $_POST['wpamt_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['wpamt_message'] ) ) : '';
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $attachments = array();

            // Handle file upload.
            if ( ! empty( $_FILES['wpamt_attachment']['tmp_name'] ) ) {
                if ( ! function_exists( 'wp_handle_upload' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                $uploaded_file = $_FILES['wpamt_attachment'];
                $upload_overrides = array( 'test_form' => false );
                $movefile = wp_handle_upload( $uploaded_file, $upload_overrides );

                if ( $movefile && ! isset( $movefile['error'] ) ) {
                    $attachment_path = $movefile['file'];
                    $attachments[] = $attachment_path;
                } else {
                    $error = $movefile['error'];
                }
            }

            // Send the email if there are no errors.
            if ( empty( $error ) ) {
                if ( wp_mail( $to, $subject, nl2br( $message ), $headers, $attachments ) ) {
                    $sent = true;
                } else {
                    global $ts_mail_errors;
                    global $phpmailer;
                    if ( ! isset( $ts_mail_errors ) ) $ts_mail_errors = array();
                    if ( isset( $phpmailer ) ) {
                        $ts_mail_errors[] = $phpmailer->ErrorInfo;
                    }
                    $error = __( 'Failed to send email. Please check your mail server configuration.', 'admin-mail-tester' );
                    $error .= ' ' . implode( ' | ', $ts_mail_errors );
                }
            }

            // Clean up the uploaded file.
            if ( ! empty( $attachment_path ) ) {
                wp_delete_file( $attachment_path );
            }
        } else {
            // Nonce verification failed.
            $error = __( 'Security check failed. Please try again.', 'admin-mail-tester' );
        }
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Admin Mail Tester', 'admin-mail-tester'); ?></h1>
        <?php if ($sent): ?>
            <div class="notice notice-success"><p><?php esc_html_e('Email sent successfully!', 'admin-mail-tester'); ?></p></div>
        <?php elseif ($error): ?>
            <div class="notice notice-error"><p><?php echo esc_html($error); ?></p></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('wpamt_mail_tester_action', 'wpamt_mail_tester_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="wpamt_to"><?php esc_html_e('To Address', 'admin-mail-tester'); ?></label></th>
                    <td><input type="email" name="wpamt_to" id="wpamt_to" required value="<?php echo esc_attr(get_option('admin_email')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="wpamt_subject"><?php esc_html_e('Subject', 'admin-mail-tester'); ?></label></th>
                    <td><input type="text" name="wpamt_subject" id="wpamt_subject" required value="<?php esc_attr_e('Test Email from Admin Mail Tester', 'admin-mail-tester'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="wpamt_message"><?php esc_html_e('Message', 'admin-mail-tester'); ?></label></th>
                    <td><textarea name="wpamt_message" id="wpamt_message" rows="5" class="large-text"><?php echo esc_textarea( __( 'This is a test email sent from your WordPress site to check mail server functionality.', 'admin-mail-tester' ) ); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="wpamt_attachment"><?php esc_html_e('Attachment (optional)', 'admin-mail-tester'); ?></label></th>
                    <td><input type="file" name="wpamt_attachment" id="wpamt_attachment"></td>
                </tr>
            </table>
            <p><input type="submit" name="wpamt_send_test_mail" class="button button-primary" value="<?php esc_attr_e('Send Test Email', 'admin-mail-tester'); ?>"></p>
        </form>
    </div>
    <?php
}
