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

//================================
// 定数
//================================
define('MSG01', '入力必須です');
define('MSG02', 'Emailの形式ではありません');
define('MSG03', 'そのEmailはすでに登録されています');
define('MSG04', '半角英数字で入力してください');
define('MSG05', '6文字以上で入力してください');
define('MSG06', '255文字以内で入力してください');
define('MSG07', 'パスワード（再入力が合っていません）');
define('MSG08', 'エラーが発生しました. 時間をおいてやり直してください');
define('MSG09', 'Emailまたはパスワードが違います');


//================================
// バリデーション関数
//================================
// エラーメッセージ表示
function getErrMsg($key){
  global $err_msg;
  if (!empty($err_msg[$key])) {
    return $err_msg[$key];
  }
}

// 未入力チェック
function validRequired($str, $key) {
  if ($str === '') {
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}
// Email形式チェック
function validEmailForm($str, $key) {
  if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
// Email重複チェック
function validEmailDup($str, $key) {
  // DB接続
  $dbh = dbConnect();
  // SQL作成
  $sql = 'SELECT * FROM Users WHERE email = :email AND delete_flg = 0';
  $data = array(':email' => $str);
  // クエリ実行
  $stmt = queryPost($dbh, $sql, $data);
  $result = $stmt->rowCount();
  if ($result != 0) {
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
// 半角英数字チェック
function validHalf($str, $key) {
  if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}
// 最小文字数チェック
function validMinLen($str, $key, $min = 6) {
  if (mb_strlen($str) < $min) {
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
// 最大文字数チェック
function validMaxLen($str, $key, $max = 255) {
  if (mb_strlen($str)> $max) {
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}
// パスワード一致チェック
function validMatch($str1, $str2, $key) {
  if ($str1 !== $str2) {
    global $err_msg;
    $err_msg[$key] = MSG07;
  }
}


//================================
// データベース
//================================
// データベース接続関数
function dbConnect() {
  // 初期設定
  $dsn = 'mysql:dbname=recipe;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  // PDOオブジェクト作成
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}
// クエリ実行関数
function queryPost($dbh, $sql, $data) {
  // クエリ作成
  $stmt = $dbh->prepare($sql);
  // プレースホルダーに値をセットして実行
  if (!$stmt->execute($data)) {
    debug('クエリ失敗');
    debug('失敗したSQL：'.print_r($stmt, true));
    $err_msg['common'] = MSG08;
    return false;
  } else {
    debug('クエリ成功');
    return $stmt;
  }

}

//================================
// その他
//================================
// フォーム入力保持
function getFormData($key) {
  if (!empty($_POST[$key])) {
    return $_POST[$key];
  }else {
    return null;
  }
}