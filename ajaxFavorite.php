<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('Ajax処理');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

//================================
// Ajax処理
//================================
// POST送信ありの場合
if (isset($_POST['productId'])) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));

  $product_id = $_POST['productId'];
  $user_id = $_SESSION['user_id'];

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT * FROM favorite WHERE product_id = :p_id AND user_id = :u_id';
    $data = array(':p_id' => $product_id, ':u_id' => $user_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->rowCount();

    // レコードが存在する場合、お気に入りから削除する
    if (!empty($result)) {
      debug('お気に入りから削除します');
      // SQL作成
      $sql = 'DELETE FROM favorite WHERE product_id = :p_id AND user_id = :u_id';
      $data = array(':p_id' => $product_id, ':u_id' => $user_id);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if ($stmt) {
        debug('削除OK');
      }
      // レコードがなければお気に入りに登録する
    } else {
      debug('お気に入りへ登録します');
      // SQL作成
      $sql = 'INSERT INTO favorite(product_id, user_id, created_at) VALUES(:p_id, :u_id, :date)';
      $data = array(
        ':p_id' => $product_id,
        ':u_id' => $user_id,
        ':date' => date('Y-m-d H:i:s'),
      );
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      if ($stmt) {
        debug('登録OK');
      }
    }

  } catch (Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }

}

debug('<<<<<Ajax処理終了<<<<<');
