<?php
/*
* Plugin Name: Password Card Generator
* Description: Erzeugt eine Passwortkarte und bindet sie per Shortcode [password_card] (oder...[password_card rows="10"]) ein. Hilfe: Die Standardfarben, Schriftfarbe und -größe können unter "Einstellungen → Password Card Generator" festgelegt werden.
* Version: 1.4
* Author: LWL3bn
* License: GNU V3.0
* Author URI: leinwandlebend.de
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

// Plugin-Pfade definieren
define( 'PCG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PCG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Klassen-Datei einbinden
require_once PCG_PLUGIN_DIR . 'src/PasswordCardGenerator.php';

// Admin Settings Page einbinden, falls im Backend
if ( is_admin() ) {
    require_once PCG_PLUGIN_DIR . 'admin/settings.php';
}

// Standardoptionen bei Aktivierung setzen
function pcg_activate() {
    $defaults = array(
        'header_bg' => '#57afc6',
        'side_bg'   => '#57afc6',
        'corner_bg' => '#000000',
        'cell_bg'   => '#ffffff',
        'font_color'=> '#000000',
        'font_size' => '11',
    );
    foreach ( $defaults as $key => $value ) {
        if ( get_option( 'pcg_' . $key ) === false ) {
            update_option( 'pcg_' . $key, $value );
        }
    }
}
register_activation_hook( __FILE__, 'pcg_activate' );

// Shortcode-Handler: Gibt das Formular + die generierte Karte aus
function pcg_shortcode_handler( $atts = [] ) {
    $atts = shortcode_atts( array(
        'rows' => 10,
    ), $atts, 'password_card' );
    $rows = intval( $atts['rows'] );

    // Hole die Standardoptionen
    $header_bg  = get_option( 'pcg_header_bg', '#57afc6' );
    $side_bg    = get_option( 'pcg_side_bg', '#57afc6' );
    $corner_bg  = get_option( 'pcg_corner_bg', '#000000' );
    $cell_bg    = get_option( 'pcg_cell_bg', '#ffffff' );
    $font_color = get_option( 'pcg_font_color', '#000000' );
    $font_size  = get_option( 'pcg_font_size', '11' );

    // Erzeuge die Karte
    $generator = new \App\PasswordCardGenerator();
    $cardHtml  = $generator->generateCard( $rows );

    ob_start();
    ?>
    <!-- Äußerer Container (für Frontend und Print) -->
    <div id="pcg-print-container">
        <!-- Inline-Style: Standardwerte werden sofort gesetzt -->
        <style>
            :root {
                --header-bg: <?php echo esc_attr( $header_bg ); ?>;
                --side-bg: <?php echo esc_attr( $side_bg ); ?>;
                --corner-bg: <?php echo esc_attr( $corner_bg ); ?>;
                --cell-bg: <?php echo esc_attr( $cell_bg ); ?>;
                --font-color: <?php echo esc_attr( $font_color ); ?>;
                --font-size: <?php echo esc_attr( $font_size ); ?>px;
            }
        </style>
        <div id="pcg-container">
            <!-- Customizer-Form -->
            <form id="pcg-customizer-form" class="pcg-customizer-form">
                <fieldset>
                    <legend>Design anpassen</legend>
                    
                    <label for="header_bg">Header Hintergrundfarbe:
                        <input type="color" id="header_bg" name="header_bg" value="<?php echo esc_attr( $header_bg ); ?>">
                    </label>
                    
                    <label for="side_bg">Seiten Hintergrundfarbe:
                        <input type="color" id="side_bg" name="side_bg" value="<?php echo esc_attr( $side_bg ); ?>">
                    </label>
                    
                    <label for="corner_bg">Ecken Hintergrundfarbe:
                        <input type="color" id="corner_bg" name="corner_bg" value="<?php echo esc_attr( $corner_bg ); ?>">
                    </label>
                    
                    <label for="cell_bg">Zellen Hintergrundfarbe:
                        <input type="color" id="cell_bg" name="cell_bg" value="<?php echo esc_attr( $cell_bg ); ?>">
                    </label>
                    
                    <label for="font_color">Schriftfarbe:
                        <input type="color" id="font_color" name="font_color" value="<?php echo esc_attr( $font_color ); ?>">
                    </label>
                    
                    <label for="font_size">Schriftgröße der Card (px):
                        <input type="number" id="font_size" name="font_size" value="<?php echo esc_attr( $font_size ); ?>" min="8" max="20">
                    </label>
                    
                    <label for="rows">Anzahl Zeilen:
                        <input type="number" id="rows" name="rows" value="<?php echo esc_attr( $rows ); ?>" min="5" max="30">
                    </label>
                </fieldset>
            </form>
            <!-- Generierte Karte -->
            <div id="card-output">
                <?php echo $cardHtml; ?>
            </div>
            <!-- Druck-Button -->
            <button class="print-button" onclick="window.print()">Drucken</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'password_card', 'pcg_shortcode_handler' );

// Skripte und Styles einbinden
function pcg_enqueue_scripts() {
    wp_enqueue_style( 'pcg-style', PCG_PLUGIN_URL . 'public/css/style.css', array(), '1.4' );
    wp_enqueue_script( 'pcg-script', PCG_PLUGIN_URL . 'public/js/customizer.js', array('jquery'), '1.4', true );
    wp_localize_script( 'pcg-script', 'pcg_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'pcg_enqueue_scripts' );

// AJAX-Handler: Generiert die Karte mit geänderter Zeilenanzahl
function pcg_update_card_ajax() {
    $rows = isset( $_POST['rows'] ) ? intval( $_POST['rows'] ) : 10;
    $generator = new \App\PasswordCardGenerator();
    echo $generator->generateCard( $rows );
    wp_die();
}
add_action( 'wp_ajax_pcg_update_card', 'pcg_update_card_ajax' );
add_action( 'wp_ajax_nopriv_pcg_update_card', 'pcg_update_card_ajax' );
