<?php
/**
 * ACF Block: Team Member Detail (light theme)
 */
if (!defined('ABSPATH')) exit;

$member     = get_field('member');
$member_id  = ($member instanceof WP_Post) ? $member->ID : (is_numeric($member) ? (int)$member : 0);

if (!$member_id): ?>
    <div class="rounded-2xl border border-dashed border-slate-300 p-6 text-slate-500 bg-white">
        <p class="text-sm">Vyberte v nastavení bloku konkrétního <strong>Team Membera</strong>.</p>
    </div>
    <?php return; endif;

$name        = get_the_title($member_id);
$position    = get_field('position', $member_id);
$desc        = get_field('description', $member_id);
$phone       = get_field('phone', $member_id);
$email       = get_field('email', $member_id);
$image_field = get_field('profile_image', $member_id);

$image_url = '';
$image_alt = $name;
if ($image_field) {
    if (is_array($image_field)) { $image_url = $image_field['url'] ?? ''; $image_alt = $image_field['alt'] ?: $name; }
    elseif (is_numeric($image_field)) { $image_url = wp_get_attachment_image_url((int)$image_field, 'medium'); $image_alt = get_post_meta((int)$image_field, '_wp_attachment_image_alt', true) ?: $name; }
    elseif (is_string($image_field)) { $image_url = $image_field; }
}

$recent_posts = function_exists('teamth_get_recent_posts_by_reviewer') ? teamth_get_recent_posts_by_reviewer($member_id, 5) : [];
?>

<section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
    <div class="flex flex-col items-center gap-6 md:flex-row md:items-start">
        <div class="shrink-0">
            <?php if ($image_url): ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"
                     class="h-32 w-32 rounded-full object-cover ring-4 ring-brand/30" />
            <?php else: ?>
                <div class="flex h-32 w-32 items-center justify-center rounded-full bg-slate-100 text-slate-400 ring-4 ring-brand/30">
                    <span class="text-3xl font-semibold"><?php echo esc_html(mb_substr($name, 0, 1)); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex-1">
            <header class="mb-4">
                <h3 class="text-2xl font-semibold text-slate-900">
                    <span class="rounded bg-brand/20 px-2 py-0.5 align-middle text-slate-900"><?php echo esc_html($name); ?></span>
                </h3>
                <?php if ($position): ?>
                    <p class="mt-1 text-slate-500"><?php echo esc_html($position); ?></p>
                <?php endif; ?>
            </header>

            <?php if ($desc): ?>
                <div class="prose prose-slate max-w-none">
                    <?php echo wp_kses_post($desc); ?>
                </div>
            <?php endif; ?>

            <ul class="mt-6 flex flex-col gap-2 text-slate-700">
                <?php if ($phone): ?>
                    <li class="flex items-center gap-2">
                        <svg class="h-5 w-5 flex-none text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M2.25 4.5c0-1.243 1.007-2.25 2.25-2.25h2.25c.993 0 1.84.654 2.11 1.602l.73 2.555a2.25 2.25 0 01-.54 2.217L7.2 10.5a16.5 16.5 0 006.3 6.3l1.876-1.85a2.25 2.25 0 012.217-.54l2.555.73A2.25 2.25 0 0121.75 18v2.25c0 1.243-1.007 2.25-2.25 2.25h-.75C9.268 22.5 1.5 14.732 1.5 5.25v-.75z"/>
                        </svg>
                        <a class="hover:underline" href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>">
                            <?php echo esc_html($phone); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($email): ?>
                    <li class="flex items-center gap-2">
                        <svg class="h-5 w-5 flex-none text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 7.5v9a2.25 2.25 0 01-2.25 2.25h-15A2.25 2.25 0 012.25 16.5v-9A2.25 2.25 0 014.5 5.25h15a2.25 2.25 0 012.25 2.25z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7.5l9 6 9-6"/>
                        </svg>
                        <a class="hover:underline" href="mailto:<?php echo esc_attr($email); ?>">
                            <?php echo esc_html($email); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if (!empty($recent_posts)): ?>
                <div class="mt-8">
                    <h4 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-600">
                        Nejnovější články s tímto recenzentem
                    </h4>
                    <ul class="list-inside list-disc space-y-1 text-slate-800">
                        <?php foreach ($recent_posts as $p): ?>
                            <li>
                                <a class="hover:underline decoration-brand decoration-2 underline-offset-2" href="<?php echo esc_url(get_permalink($p)); ?>">
                                    <?php echo esc_html(get_the_title($p)); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
