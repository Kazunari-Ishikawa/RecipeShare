<?php
//================================
// ログ設定
//================================
ini_set('log_errors','on');
ini_set('error_log','php.log');

//================================
// デバッグ設定
//================================
// デバッグフラグ
$debug_flg = true;
// デバッグログ関数
function debug($str) {
  global $debug_flg;
  if ($debug_flg === true) {
    error_log($str);
  }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime ', 60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();

//================================
// 画面処理開始ログ出力関数
//================================
function debugLogStart() {
  debug('>>>>>画面表示処理開始>>>>>');
  if (!empty($_SESSION)) {
    debug('セッションID：'.session_id());
    debug('セッション変数：'.print_r($_SESSION, true));
    debug('現在日時：'.time());
    if (!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])) {
      debug('ログイン期限：'.($_SESSION['login_date'] + $_SESSION['login_limit']));
    }
  }
}

//================================
// グローバル変数
//================================
//エラーメッセージ格納用の配列
$err_msg = array();
// デバッグ用
$test = 0;