<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main main-content">
        <?php
        if (is_front_page()) {
            ?>
            <div class="welcome-message">
                <h1 class="welcome-title">¡Bienvenido a Nuestro Sitio Web!</h1>
                <p class="welcome-text">Estamos encantados de tenerte aquí. Explora nuestros libros y mantente al tanto de los precios actuales de Bitcoin con nuestro widget en el lado derecho.</p>
            </div>
            <?php
        }

        if (have_posts()) :
            while (have_posts()) : the_post();
                get_template_part('template-parts/content', get_post_format());
            endwhile;
        else :
            get_template_part('template-parts/content', 'none');
        endif;
        ?>
    </main>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
