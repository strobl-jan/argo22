<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @param int $member_id  ID team membera
 * @param int $limit      Kolik příspěvků vrátit
 * @return WP_Post[]
 */
function teamth_get_recent_posts_by_reviewer(int $member_id, int $limit = 5): array
{
    if (!$member_id) {
        return [];
    }

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        'meta_query'     => [
            [
                'key'     => 'reviewer',
                'value'   => '"' . $member_id . '"',
                'compare' => 'LIKE',
            ]
        ]
    ];

    return get_posts($args);
}

function teamth_e($string): string
{
    return esc_html($string ?? '');
}
