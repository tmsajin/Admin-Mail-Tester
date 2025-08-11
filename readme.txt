=== Admin Mail Tester ===
Contributors: wpsajin
Tags: mail, email, test, admin, attachment
Requires at least: 5.2
Tested up to: 6.8
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple plugin to send a test email with an attachment from the WordPress admin to verify email functionality.

== Description ==

The Admin Mail Tester plugin provides a straightforward way for site administrators to test if their WordPress installation is sending emails correctly. It adds a simple page in the admin dashboard where you can:

*   Specify a recipient email address.
*   Write a custom subject and message.
*   Optionally attach a file.
*   Send the email and see a confirmation or error message.

For more details, please visit the [plugin's GitHub page](https://github.com/tmsajin/WP-Admin-Mail-Tester).

== Installation and Usage ==

1.  Upload the `admin-mail-tester` folder to the `/wp-content/plugins/` directory via FTP or upload the ZIP file through the WordPress admin dashboard at 'Plugins' -> 'Add New'.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Once activated, you can find the plugin's page under the "Tools" menu in your WordPress admin dashboard.

To send a test email:

1.  Navigate to **Tools -> Mail Tester**.
2.  The "To Address" field will be pre-filled with the site admin's email, but you can change it to any email address you want to test.
3.  Fill in the "Subject" and "Message" for your test email.
4.  Optionally, click the "Choose File" button to select a file from your computer to send as an attachment.
5.  Click the "Send Test Email" button.
6.  The page will reload and display a success or failure message at the top, which will help you confirm if your site's email functionality is working correctly.

== Frequently Asked Questions ==

= Can I attach any file type? =

Yes, you can attach any file type that is allowed by your server's PHP configuration.

= Where do I find the settings page? =

In your WordPress admin dashboard, navigate to **Tools -> Mail Tester**.

== Screenshots ==

1. The main interface of the Admin Mail Tester plugin, showing the fields for recipient, subject, message, and attachment.

== Changelog ==

= 1.0.0 =
*   Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release of the plugin.
