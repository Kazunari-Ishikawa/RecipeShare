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
define('MSG10', '不正な値です');
define('MSG11', '半角数字のみ利用できます');
define('MSG12', '現在のパスワードと同じです');
define('MSG13', '登録されているパスワードと違います');
define('MSG14', 'カテゴリを選択して下さい');

define('SUC01', 'プロフィールを更新しました');
define('SUC02', 'パスワードを変更しました');
define('SUC03', 'ご飯を登録しました');

//================================
// バリデーション関数
//================================
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
// パスワード関連のチェック
function validPass($str, $key) {
  validMinLen($str, $key);
  validMaxLen($str, $key);
  validHalf($str, $key);
}
// 性別チェック
function validSex($num, $key) {
  // male or female以外の値が送られてきた場合
  if ($num !== 0) {
  } elseif ($num !== 1) {
  } else {
    global $err_msg;
    $err_msg[$key] = MSG10;
  }
}
// 半角数字チェック
function validNum($str, $key) {
  if (!preg_match("/^[0-9]+$/", $str)) {
    global $err_msg;
    $err_msg[$key] = MSG11;
  }
}
// カテゴリセレクトボックスチェック
function validSelect($str, $key) {
  if (!preg_match("/^[1-9]+$/", $str)) {
    global $err_msg;
    $err_msg[$key] = MSG14;
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
// ユーザー情報取得関数
function getUser($user_id) {
  debug('ユーザー情報を取得します');
  debug('ユーザーID：'.print_r($user_id, true));

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT * FROM Users WHERE id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $user_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // データ取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stmt) {
      return $result;
    } else {
      return false;
    }

  } catch(Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }
}
// プロダクト情報取得関数
function getProduct($product_id) {
  debug('プロダクト情報を取得します');
  debug('プロダクトID：'.print_r($product_id, true));

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT * FROM Recipe WHERE id = :p_id AND delete_flg = 0';
    $data = array(':p_id' => $product_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // データ取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt) {
      return $result;
    } else {
      return false;
    }

  } catch(Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }
}
// プロダクト＋カテゴリ情報取得関数
function getProductAndCategory($product_id) {
  debug('プロダクトとカテゴリ情報を取得します');
  debug('プロダクトID：'.print_r($product_id, true));

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT c.name AS category, r.main_name, r.sub_name, r.comment, r.pic, r.user_id FROM Recipe AS r LEFT JOIN category AS c ON r.category_id = c.id WHERE r.id = :p_id AND r.delete_flg = 0 AND c.delete_flg = 0';
    $data = array(':p_id' => $product_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // データ取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt) {
      return $result;
    } else {
      return false;
    }

  } catch(Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }
}
// カテゴリデータ取得関数
function getCategory() {
  debug('カテゴリデータを取得します');

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT * FROM category WHERE delete_flg = 0';
    $data = array();
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // データ取得
    $result = $stmt->fetchAll();
    if ($stmt) {
      return $result;
    } else {
      return false;
    }

  } catch(Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }
}

//================================
// その他
//================================
// エラーメッセージ表示
function getErrMsg($key){
  global $err_msg;
  if (!empty($err_msg[$key])) {
    return $err_msg[$key];
  }
}
// フォーム入力保持
function getFormData($key) {
  global $dbUserData;
  global $dbProductData;
  if (!empty($_GET)) {
    return $dbProductData[$key];
  }
  // DBにデータがある場合、それを返す
  if (!empty($dbUserData[$key])) {
    return $dbUserData[$key];
  }
  if (!empty($_POST[$key])) {
    return $_POST[$key];
  }
  if (empty($_POST[$key])) {
    return null;
  }
}
// 画像アップロード関数
function uploadImg($file, $key) {
  debug('画像アップロード開始');

  if (isset($file['error']) && is_int($file['error'])) {

    try {
      // ファイルのエラーコード確認
      switch ($file['error']) {
        case UPLOAD_ERR_OK:
          break;
        case UPLOAD_ERR_INI_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます');
        case UPLOAD_ERR_FORM_SIZE:
          throw new RuntimeException('ファイルサイズが大きすぎます');
        case UPLOAD_ERR_NO_FILE:
          throw new RuntimeException('ファイルが選択されていません');
        default :
          throw new RuntimeException('予期せぬエラーが発生しました');
      }

      // ファイルのMIMEタイプ確認
      $type = @exif_imagetype($file['tmp_name']);
      if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
        throw new RuntimeException('画像形式が未対応です');
      }

      // ハッシュをつけてファイル名を保存する
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
      if (!move_uploaded_file($file['tmp_name'], $path)) {
        throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }

      // 保存したファイルの権限を変更する
      chmod($path, 0644);

      debug('アップロード完了');
      debug('ファイルパス：'.print_r($path,true));
      return $path;

    } catch (RuntimeException $e) {
      debug('エラー：'.$e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();

    }
  }
}
