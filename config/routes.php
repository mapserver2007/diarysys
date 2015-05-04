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
    '/search' => "entry#entry_search",

    '/api/html/entry/monthly_archive' => "entry_api#entry_monthly_archive",
    '/api/html/entry/tag_list' => "entry_api#entry_tag_list",

    '/manage' => "manage#index"
]);
