<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('料理削除処理');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

//================================
// 画面表示処理
//================================
// POST送信がある場合
if (isset($_POST['productId'])) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));

  $product_id = $_POST['productId'];
  $user_id = $_SESSION['user_id'];

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'UPDATE Recipe SET delete_flg = 1 WHERE user_id = :u_id AND id = :p_id';
    $data = array(':u_id' => $user_id, ':p_id' => $product_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      debug('削除処理成功');
      debug('マイページへ遷移');
      header("Location:mypage.php");
      exit();
    } else {
      $err_msg['common'] = MSG08;
    }

  } catch(Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }
}

debug('<<<<<Ajax処理終了<<<<<');
