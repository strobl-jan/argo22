<?php
if (!defined('ABSPATH')) exit;
get_header();

while (have_posts()): the_post();
    $id   = get_the_ID();


    $position      = get_field('position', $id);
    $email         = get_field('email', $id);
    $phone         = get_field('phone', $id);
    $description   = get_field('description', $id); // WYSIWYG
    $profile_image = get_field('profile_image', $id); // array|id|null


    $img_html = '';
    if ($profile_image) {
        if (is_array($profile_image) && isset($profile_image['ID'])) {
            $img_html = wp_get_attachment_image($profile_image['ID'], 'large', false, ['class' => 'w-full h-full object-cover']);
        } elseif (is_numeric($profile_image)) {
            $img_html = wp_get_attachment_image((int)$profile_image, 'large', false, ['class' => 'w-full h-full object-cover']);
        }
    } elseif (has_post_thumbnail($id)) {
        $img_html = get_the_post_thumbnail($id, 'large', ['class' => 'w-full h-full object-cover']);
    }


    $email_display = $email ? antispambot($email) : '';
    $email_href    = $email ? 'mailto:' . antispambot($email) : '';
    $phone_clean   = $phone ? preg_replace('/\s+/', '', (string)$phone) : '';
    $phone_href    = $phone ? 'tel:' . esc_attr($phone_clean) : '';


    $back_url = wp_get_referer();
    if (!$back_url) $back_url = home_url('/');


    $assigned_posts = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            'relation' => 'OR',
            [
                'key'     => 'reviewer',
                'value'   => $id,
                'compare' => '='
            ],
            [
                'key'     => 'reviewer_id',
                'value'   => $id,
                'compare' => '='
            ],
            [
                'key'     => 'assigned_reviewer',
                'value'   => $id,
                'compare' => '='
            ],

            [
                'key'     => 'reviewers',
                'value'   => '"' . $id . '"',
                'compare' => 'LIKE'
            ],
        ],
        'no_found_rows'  => true,
    ]);
    ?>
    <main class="max-w-6xl mx-auto px-4 md:px-6 py-10">


        <section class="mb-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 md:p-6 shadow-card">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <a href="<?php echo esc_url($back_url); ?>" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                        Zpět
                    </a>
                </div>
            </div>
        </section>

        <!-- Detail člena -->
        <article class="grid gap-8 md:grid-cols-[340px_1fr]">
            <div>
                <div class="overflow-hidden rounded-2xl bg-slate-100 aspect-[4/5] ring-4 ring-brand/20">
                    <?php echo $img_html; ?>
                </div>


                <?php if ($email || $phone): ?>
                    <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="text-base font-semibold text-slate-900 mb-3">Kontakt</h2>
                        <div class="space-y-2 text-slate-700">
                            <?php if ($email): ?>
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 17.25V6.75M21.75 6.75l-9.75 6.75L2.25 6.75M21.75 6.75H2.25"/>
                                    </svg>
                                    <a href="<?php echo esc_url($email_href); ?>" class="hover:underline decoration-brand underline-offset-2">
                                        <?php echo esc_html($email_display); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if ($phone): ?>
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M2.25 4.5c0-1.243 1.007-2.25 2.25-2.25h2.25c.993 0 1.84.654 2.11 1.602l.73 2.555a2.25 2.25 0 0 1-.54 2.217L7.2 10.5a16.5 16.5 0 0 0 6.3 6.3l1.876-1.85a2.25 2.25 0 0 1 2.217-.54l2.555.73A2.25 2.25 0 0 1 21.75 18v2.25c0 1.243-1.007 2.25-2.25 2.25h-.75C9.268 22.5 1.5 14.732 1.5 5.25v-.75z"/>
                                    </svg>
                                    <a href="<?php echo esc_url($phone_href); ?>" class="hover:underline decoration-brand underline-offset-2">
                                        <?php echo esc_html($phone); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <header class="mb-5">
                    <h1 class="text-3xl md:text-4xl font-extrabold leading-tight"><?php echo esc_html(get_the_title()); ?></h1>
                    <?php if ($position): ?>
                        <p class="mt-1 text-slate-600 text-lg"><?php echo esc_html($position); ?></p>
                    <?php endif; ?>
                </header>

                <div class="prose prose-slate max-w-none">
                    <?php
                    if (!empty($description)) {
                        echo wp_kses_post($description);
                    } else {
                        the_content(); // fallback
                    }
                    ?>
                </div>

                <section class="mt-10">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Přiřazené příspěvky</h2>

                    <?php if ($assigned_posts->have_posts()): ?>
                        <ul class="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <?php while ($assigned_posts->have_posts()): $assigned_posts->the_post(); ?>
                                <li class="p-4 hover:bg-slate-50 transition">
                                    <a class="block"
                                       href="<?php the_permalink(); ?>">
                                        <div class="flex items-baseline justify-between gap-4">
                                            <span class="font-semibold text-slate-900"><?php the_title(); ?></span>
                                            <time class="text-sm text-slate-500"><?php echo esc_html(get_the_date()); ?></time>
                                        </div>
                                        <?php if (has_excerpt()): ?>
                                            <p class="mt-1 text-sm text-slate-600 line-clamp-2"><?php echo esc_html(get_the_excerpt()); ?></p>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-slate-600">Tento člen nemá žádné přiřazené příspěvky.</p>
                    <?php endif; ?>
                </section>
            </div>
        </article>

        <!-- Page Footer -->
        <section class="mt-10">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 md:p-6 shadow-card">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="text-sm text-slate-600">
                        <span class="font-medium text-slate-900"><?php echo esc_html(get_the_title()); ?></span><?php
                        if ($position) echo ' — ' . esc_html($position); ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if ($email): ?>
                            <a href="<?php echo esc_url($email_href); ?>" class="inline-flex items-center gap-2 rounded-xl border px-3 py-1.5 text-sm hover:shadow-sm">
                                <!-- mail icon -->
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 17.25V6.75M21.75 6.75l-9.75 6.75L2.25 6.75M21.75 6.75H2.25"/>
                                </svg>
                                Napsat e-mail
                            </a>
                        <?php endif; ?>
                        <?php if ($phone): ?>
                            <a href="<?php echo esc_url($phone_href); ?>" class="inline-flex items-center gap-2 rounded-xl border px-3 py-1.5 text-sm hover:shadow-sm">
                                <!-- phone icon -->
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M2.25 4.5c0-1.243 1.007-2.25 2.25-2.25h2.25c.993 0 1.84.654 2.11 1.602l.73 2.555a2.25 2.25 0 0 1-.54 2.217L7.2 10.5a16.5 16.5 0 0 0 6.3 6.3l1.876-1.85a2.25 2.25 0 0 1 2.217-.54l2.555.73A2.25 2.25 0 0 1 21.75 18v2.25c0 1.243-1.007 2.25-2.25 2.25h-.75C9.268 22.5 1.5 14.732 1.5 5.25v-.75z"/>
                                </svg>
                                Zavolat
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($back_url); ?>" class="inline-flex items-center gap-2 rounded-xl border px-3 py-1.5 text-sm hover:shadow-sm">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                            Zpět
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php
endwhile;

get_footer();
