/**
 * diarysys5専用モジュール定義
 */
(function(window, $) {

if (window.Blog) return;

window.Blog = {};
window.Blog.PC = {};
window.Blog.SP = {}; // そのうち実装

/**
 * 共通設定モジュール
 */
Mixjs.module("Common", Blog, {
    /** ドキュメントルート */
    _root: "/diarysys",

    /** ホスト */
    _host: location.protocol + "//" + location.host,

    /** CookieSuffix */
    _cookieSuffix: ".diarysys.summer-lights.jp",

    /** JSONキャッシュ時間 */
    _jsonCacheExpire: {hour: 1},
});

/**
 * 表側(PC)モジュール
 */
Mixjs.interface(Blog.Common).module("Entry", Blog.PC, {
    /** 依存モジュール */
    include: Http,

    /**
     * 初期処理
     */
    initialize: function() {
        this.event();
    },

    /**
     * イベントを設定
     */
    event: function() {
    },


    monthlyArchive: function() {
        var self = this;
        this.xhr({
            url: this._root + "/api/html/entry/monthly_archive",
            args: {type: "get", dataType: "html"},
            success: function(res) {
                $("#monthly_archive").html(res);
            }
        });

        return this;
    },

    tagList: function() {
        var self = this;
        this.xhr({
            url: this._root + "/api/html/entry/tag_list",
            args: {type: "get", dataType: "html"},
            success: function(res) {
                $("#tag_list").html(res);
            }
        });

        return this;
    }
});


})(window, jQuery);