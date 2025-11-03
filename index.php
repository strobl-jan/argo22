<?php
if (!defined('ABSPATH')) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-50 text-slate-900 antialiased'); ?>>

<a class="sr-only focus:not-sr-only" href="#content">Skip to content</a>

<header class="border-b border-slate-200 bg-white/80 backdrop-blur">
    <div class="mx-auto max-w-5xl px-4 py-4">
        <div class="flex items-center justify-between">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-lg font-semibold hover:underline">
                <?php bloginfo('name'); ?>
            </a>
            <?php if (has_nav_menu('primary')): ?>
                <nav class="text-sm">
                    <?php wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'flex gap-4',
                        'fallback_cb'    => false
                    ]); ?>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</header>

<main id="content" class="mx-auto max-w-5xl px-4 py-10">
    <?php if (is_singular()): ?>
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
            <article <?php post_class('prose prose-slate max-w-none dark:prose-invert'); ?>>
                <?php if (!is_page()): ?>
                    <h1 class="mb-6 text-3xl font-bold"><?php the_title(); ?></h1>
                <?php endif; ?>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; endif; ?>
    <?php else: ?>
        <?php if (have_posts()): ?>
            <div class="space-y-10">
                <?php while (have_posts()): the_post(); ?>
                    <article <?php post_class('rounded-2xl border border-slate-200 bg-white p-6 shadow-sm'); ?>>
                        <header class="mb-3">
                            <h2 class="text-2xl font-semibold">
                                <a class="hover:underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="text-sm text-slate-500">
                                <?php echo esc_html(get_the_date()); ?>
                            </p>
                        </header>
                        <div class="prose prose-slate max-w-none line-clamp-4">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>

                <div class="mt-8">
                    <?php the_posts_pagination([
                        'mid_size'  => 1,
                        'prev_text' => '←',
                        'next_text' => '→',
                    ]); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
                Zatím tu nic není.
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<footer class="mt-16 border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-5xl px-4 py-6 text-sm text-slate-500">
        © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
