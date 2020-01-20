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
if (!empty($_POST)) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'UPDATE Recipe SET delete_flg = 1 WHERE user_id = :u_id AND id = :p_id';
    $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $product_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
      debug('削除処理成功');
      debug('マイページへ遷移');
      header("Location:mypage.php");
    } else {
      $err_msg['common'] = MSG08;
    }

  } catch(Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }
}

?>

<?php
$siteTitle = '退会';
require('head.php');
?>

  <body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メイン -->
    <main id="productDetail" class="layout-1-column">

    
    <!-- メインコンテンツ -->
    <section id="detail">
      <div class="detail-container">
        <div class="detail-container-top">
          <a href="mypage.php">前ページへ戻る</a>
        </div>

        </div>
      </div>
    </section>
  </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>