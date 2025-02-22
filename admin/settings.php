<?php
// admin/settings.php

// Registrierung der Einstellungen
function pcg_register_settings() {
    register_setting( 'pcg_settings_group', 'pcg_header_bg' );
    register_setting( 'pcg_settings_group', 'pcg_side_bg' );
    register_setting( 'pcg_settings_group', 'pcg_corner_bg' );
    register_setting( 'pcg_settings_group', 'pcg_cell_bg' );
    register_setting( 'pcg_settings_group', 'pcg_font_color' );
    register_setting( 'pcg_settings_group', 'pcg_font_size' );
}
add_action( 'admin_init', 'pcg_register_settings' );

// Hinzufügen der Einstellungsseite
function pcg_add_settings_page() {
    add_options_page(
        'Password Card Generator Settings',
        'Password Card Generator',
        'manage_options',
        'pcg-settings',
        'pcg_settings_page_callback'
    );
}
add_action( 'admin_menu', 'pcg_add_settings_page' );

// Callback für die Einstellungsseite inkl. Hilfetext
function pcg_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>Password Card Generator Settings</h1>
        <p>Hier können Sie die Standardwerte für den Password Card Generator festlegen. Diese Werte werden im Frontend als Ausgangsfarben, Schriftfarbe und Schriftgröße verwendet. Änderungen wirken sich ab dem nächsten Aufruf der Seite aus.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'pcg_settings_group' ); ?>
            <?php do_settings_sections( 'pcg_settings_group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Header Hintergrundfarbe</th>
                    <td><input type="color" name="pcg_header_bg" value="<?php echo esc_attr( get_option('pcg_header_bg', '#57afc6') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Seiten Hintergrundfarbe</th>
                    <td><input type="color" name="pcg_side_bg" value="<?php echo esc_attr( get_option('pcg_side_bg', '#57afc6') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Ecken Hintergrundfarbe</th>
                    <td><input type="color" name="pcg_corner_bg" value="<?php echo esc_attr( get_option('pcg_corner_bg', '#000000') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Zellen Hintergrundfarbe</th>
                    <td><input type="color" name="pcg_cell_bg" value="<?php echo esc_attr( get_option('pcg_cell_bg', '#ffffff') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Schriftfarbe</th>
                    <td><input type="color" name="pcg_font_color" value="<?php echo esc_attr( get_option('pcg_font_color', '#000000') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Schriftgröße (px)</th>
                    <td><input type="number" name="pcg_font_size" value="<?php echo esc_attr( get_option('pcg_font_size', '11') ); ?>" min="8" max="20" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
