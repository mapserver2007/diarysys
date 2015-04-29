<?php
namespace WebStream\Delegate;

/**
 * ルーティングルールを記述する
 */
Router::setRule([
    '/' => "entry#entry",
    '/entry/:entry_id' => "entry#entry_by_id",
    '/tag/:tag_name' => "entry#entry_by_tag",
    '/category/:category_name' => "entry#entry_by_category",
    '/month/:yyyymm' => "entry#entry_by_month",
    '/api/entry/monthly_count' => "entry_api#entry_monthly_count",
    '/api/entry/tag_count' => "entry_api#entry_tag_count"
]);
