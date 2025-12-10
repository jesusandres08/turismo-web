<?php
/**
 * Sidebar Template
 */

if ( is_active_sidebar( 'primary-sidebar' ) ) {
    ?>
    <aside class="primary-sidebar">
        <?php dynamic_sidebar( 'primary-sidebar' ); ?>
    </aside><!-- .primary-sidebar -->
    <?php
}
?>