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
// Mixjs.module("Common", Blog, {
//     /** ドキュメントルート */
//     _root: "/diarysys",

//     /** ホスト */
//     _host: location.protocol + "//" + location.host,

//     /** CookieSuffix */
//     _cookieSuffix: ".diarysys.summer-lights.jp",

//     /** JSONキャッシュ時間 */
//     _jsonCacheExpire: {hour: 1}
// });

Mixjs.module("Util", Blog.PC, {
    /**
     * ブラウザ表示用に変換する
     * @param String 変換対象文字列
     * @param Array 変換記号
     * @return String 変換後文字列
     */
    toEntryReference: function(str, optTargets) {
        if (str === "") {
            return;
        }

        var target = typeof optTargets !== "undefined" ?
            optTargets : ['\n', '&', '<', '>', '\''];
        for (var i = 0; i < target.length; i++) {
            var cond = target[i];
            //&amp;
            if (cond == "&")
                str = str.split('&').join('&amp;');
            //&lt;
            if (cond == "<")
                str = str.split('<').join('&lt;');
            //&gt;
            if (cond == "\'")
                str = str.split('>').join('&gt;');
            //&quot;
            if (cond == "\n")
                str = str.split('\'').join('&quot;');
        }
        // 改行コードだけはHTMLタグにする
        str = str.split('\n').join('<br/>');

        return str;
    }
});

/**
 * 共通モジュール
 */
Mixjs.module("Entry", Blog.PC, {
    /** 依存モジュール */
    include: Http,

    /**
     * 初期処理
     */
    initialize: function() {
    },

    /**
     * イベントを設定
     */
    event: function() {
        var self = this;
        $("#entry_search").on("keypress", function(e) {
            if (e.which === 13){
                var text = $("#entry_search").val();
                if (self.bytesize(text) <= 3) {
                    alert("検索語が短すぎます。");
                    return;
                }
                location.href = "/diarysys/search?q=" + text;
            }
        });
    }
});

/**
 * 管理画面
 * エントリ入力画面 リアルタイムプレビュー
 */
Mixjs.module("RealTimePreview", Blog.PC, {
    /** 依存モジュール */
    include: [Blog.PC.Util],

    /** ターゲットウィンドウ */
    targetWindow: null,

    /**
     * プレビューを実行する
     */
    onPreview: function() {
        var self = this,
            data = self.getPreviewData();

        if (this.targetWindow !== null) {
            this.targetWindow.close();
            this.targetWindow = null;
        }

        this.targetWindow  = window.open("/diarysys/manage/entry_create/preview",
                                         "preview",
                                         "width=1200 height=900");
        $(this.targetWindow).on("beforeunload", function(e) {
            self.targetWindow = null;
        });

        setTimeout(function() { self.postMessage(data); }, 1000);
    },

    /**
     * リアルタイムプレビューを有効にする
     */
    onRealtimePreview: function() {
        var self = this;
        $("#title, #description").keyup(function(event) {
            if ($("#realtime").is(":checked")) {
                self.postMessage(self.getPreviewData());
            }
        });
        $("#mode_bbcode, #mode_markdown").on("click", function() {
            if ($("#realtime").is(":checked")) {
                self.postMessage(self.getPreviewData());
            }
        });
        $("#tag_list input[type='checkbox']").each(function() {
            $(this).on("click", function() {
                self.postMessage(self.getPreviewData());
            });
        });
        $("#category input[type='radio']").each(function() {
            $(this).on("click", function() {
                self.postMessage(self.getPreviewData());
            });
        });
    },

    /**
     * プレビューに使用するデータを取得する
     * @return Hash プレビューデータ
     */
    getPreviewData: function() {
        var title = this.toEntryReference($("#title").val()),
            description = $("#description").val(),
            category = $("#category input[type='radio']:checked").data("name");

        var tags = [];
        $("#tag_list input[type='checkbox']:checked").each(function() {
            tags.push($(this).data("name"));
        });
        var tag = tags.join("&nbsp;");

        if ($("#image_no_preview").is(":checked")) {
            if ($("#mode_bbcode").is(":checked")) {
                // TODO
            } else {
                description = description.replace(/!\[.+?\]\(.+?\)/g, "[画像]");
            }
        }


        // for (var i = 0; i < this.selectedTags.length; i++) {
        //     var tagId = "tag_" + this.selectedTags[i];
        //     tags.push($("li#" + tagId + " span").text());
        // }
        // tag = tags.join(" ");

        // if ($("#image_no_preview").is(":checked")) {
        //     text = noImagePreview(text);
        // }
        var data = {
            title: title || "(タイトルを入力してください)",
            description: description || "(本文を入力してください)",
            category: category,
            tag: tag || "(タグ未設定)",
            mode: {
                bbcode: $("#mode_bbcode").is(":checked"),
                markdown: $("#mode_markdown").is(":checked")
            }
        };
        return data;
    },

    /**
     * メッセージをプレビュー画面に送信する
     * @param Hash 送信データ
     */
    postMessage: function(data) {
        if (this.targetWindow !== null) {
            this.targetWindow.postMessage(data, location.protocol + "//" + location.host);
        }
    },

    /**
     * メッセージを受信しプレビュー画面に反映する
     * @param Function メッセージ受信後に実行する関数
     */
    receiveMessage: function(callback) {
        var self = this;
        $(window).on("message", function(e) {
            callback.call(self, e.originalEvent.data);
        });
    }
});

/**
 * 管理画面
 * エントリ入力画面 アップロード画像一覧
 */
Mixjs.module("UploadImage", Blog.PC, {
    /** ページ番号 */
    _imageListPage: 1,

    /**
     * 登録済み画像一覧画面を開く
     */
    onLoadUploadImage: function() {
        window.open("/diarysys/manage/entry_create/image",
                    "upload_image",
                    "width=1200 height=700");
    }

});

/**
 * 管理画面モジュール
 */
Mixjs.module("Manage", Blog.PC, {
    /** 依存モジュール */
    include: [Blog.PC.RealTimePreview, Blog.PC.UploadImage, Http, Cookie],

    /**
     * 初期処理
     */
    initialize: function() {
    },

    /**
     * エントリ一覧画面
     */
    entryListPage: function() {
        var self = this;
        // 検索イベント
        $("#entry_search").on("keypress", function(e) {
            if (e.which === 13){
                var text = $("#entry_search").val();
                if (self.bytesize(text) <= 3) {
                    alert("検索語が短すぎます。");
                    return;
                }
                location.href = "/diarysys/manage?q=" + text;
            }
        });
    },

    /**
     * エントリ作成画面
     */
    entryCreate: function() {
        var self = this;
        var entryOptionKey = "ENRYOPTION";
        var data = this.deserialize(this.getCookie(entryOptionKey));

        if (data !== null) {
            data.bbcode ? $("#mode_bbcode").trigger("click") : $("#mode_markdown").trigger("click");
            if (data.realtime) {
                $("#realtime").attr('checked', true);
            } else {
                $("#realtime").removeAttr('checked');
            }
            if (data.image) {
                $("#image_no_preview").attr('checked', true);
            } else {
                $("#image_no_preview").removeAttr('checked');
            }
        }

        $("#preview").on("click", function() {
            self.onPreview();
            self.onRealtimePreview();
        });

        $("#upload_image").on("click", function() {
            self.onLoadUploadImage();
        });

        $("#realtime, #image_no_preview, #mode_bbcode, #mode_markdown").on("click", function() {
            self.setCookie(entryOptionKey, self.serialize({
                'bbcode': $("#mode_bbcode").is(":checked"),
                'realtime': $("#realtime").is(":checked"),
                'image': $("#image_no_preview").is(":checked")
            }), {day: 1}, self.host, '/diarysys/manage');
            if ($("#realtime").is(":checked")) {
                self.postMessage(self.getPreviewData());
            }
        });

        $("#save_cache").on("click", function() {
            self.xhr({
                url: "/diarysys/api/manage/entry_cache/create",
                args: {type: "post", dataType: "json", cache: false},
                params: {
                    title: $("#title").val(),
                    description: $("#description").val()
                },
                success: function(res) {
                    if (res.result) {
                        alert("データを保存しました。")
                    }
                }
            });
        });

        $("#read_cache").on("click", function() {
            self.xhr({
                url: "/diarysys/api/manage/entry_cache/read",
                args: {type: "get", dataType: "json", cache: false},
                success: function(res) {
                    if (res.data === null) {
                        alert("データがありませんでした。");
                    } else {
                        $("#title").val(res.data.title);
                        $("#description").val(res.data.description);
                        if ($("#realtime").is(":checked")) {
                            self.postMessage(self.getPreviewData());
                        }
                    }
                }
            });
        });
    },

    /**
     * エントリ作成プレビュー画面
     */
    entryCreatePreview: function() {
        var self = this;
        this.receiveMessage(function(data) {
            $("#title_preview").html(data["title"]);
            // $("#description_preview").html(data["description"]);
            var description = data["description"];
            if (data.mode.bbcode) {
                description = getStringCommentTags(getCommentTags(edButtons), self.toEntryReference(description));
            } else if (data.mode.markdown) {
                description = marked(description);
            }
            $("#description_preview").html(description);
            $("#category_preview").html(data["category"]);
            $("#tag_preview").html(data["tag"]);

            // $("#description_preview").html(self.bb2html(data["text"]));
            // $("#tag_preview").html(data["tag"]);
            // self.fancybox();
            // self.codeHighlight();
            // $("a.fancybox").each(function() {
            //     if (/(.*)small\/(.*)/.test(this.href)) {
            //         var href = RegExp.$1 + RegExp.$2;
            //         $(this).attr("href", href);
            //     }
            // });
        });
    },

    /**
     * エントリ削除確認画面
     */
    entryDeleteConfirm: function() {
        var self = this;
        // エントリ一覧画面への戻り
        $("#back_to_entry_list").on("click", function() {
            location.href = "/diarysys/manage";
        });
    },

    /**
     * アップロード画像選択画面
     */
    entryCreateImage: function() {
        var self = this;
        $("#next").on("click", function() {
            self.xhr({
                url: "/diarysys/api/html/manage/load_upload_image",
                args: {type: "get", dataType: "html", cache: true},
                params: {p: self._imageListPage++, num: $("#image_num").data("num")},
                success: function(html) {
                    if (html === "") {
                        alert("もう画像はないよ。");
                    } else {
                        $("#image_list").append(html);
                    }
                }
            });
        }).trigger("click");
    },

    /**
     * 選択画像をフォームに追加
     */
    onSelectImage: function(name) {
        var self = this;
        var code = "";
        var path = "/diarysys/img/upload/" + name;
        var smallPath = "/diarysys/img/upload/small/" + name;
        if ($("#mode_bbcode").is(":checked")) {
            // TODO
        } else {
            code = "[![" + smallPath + "](" + smallPath + ")](" + path + ")\n";
        }

        $("#description").val($("#description").val() + code);
        if ($("#realtime").is(":checked")) {
            self.postMessage(self.getPreviewData());
        }
    },

    /**
     * 画像選択
     */
    selectImage: function(self) {
        window.opener.Blog.PC.Manage.onSelectImage($(self).data("name"));
    },
});

})(window, jQuery);