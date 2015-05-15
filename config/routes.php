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

    '/manage' => "manage#index",
    '/manage/entry_create' => "manage#entry_create",
    '/manage/entry_create/preview' => "manage#entry_create_preview",
    '/manage/entry_create/image' => "manage#entry_create_image",
    '/manage/entry_delete/confirm' => "manage#entry_delete_confirm",
    '/manage/entry_delete/complete' => "manage#entry_delete_complete",

    '/api/html/manage/entry_create/tags' => "manage_api#entry_create_tags",
    '/api/html/manage/entry_create/category' => "manage_api#entry_create_category",
    '/api/html/manage/load_upload_image' => "manage_api#load_upload_image",
    '/api/manage/entry_cache/read' => "manage_api#entry_cache_read",
    '/api/manage/entry_cache/create' => "manage_api#entry_cache_create"
]);
