<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('退会ページ');
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
    $sql1 = 'UPDATE Users SET delete_flg = 1 WHERE id = :u_id';
    $sql2 = 'UPDATE Recipe SET delete_flg = 1 WHERE user_id = :u_id';
    $sql3 = 'UPDATE favorite SET delete_flg = 1 WHERE user_id = :u_id';
    $data = array(':u_id' => $_SESSION['user_id']);
    // クエリ実行
    $stmt1 = queryPost($dbh, $sql1, $data);
    $stmt2 = queryPost($dbh, $sql2, $data);
    $stmt3 = queryPost($dbh, $sql3, $data);

    // Usersテーブルのクエリが成功すればOKと判定する
    if ($stmt1) {
      debug('退会処理成功');
      session_destroy();
      debug('セッション変数：'.print_r($_SESSION,true));
      debug('トップページへ遷移');
      header("Location:index.php");
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
    <main id="" class="layout-2-column">
      <h1 class="page-title"><?php echo $siteTitle; ?></h1>
      <!-- サイドバー -->
      <?php require('setting-sidebar.php'); ?>

      <!-- メインコンテンツ -->
      <section id="contents" class="layout-2-column-right">
        <div class="form-container small-form">
          <form action="" method="post">
          <div class="msg-area"><?php echo getErrMsg('common'); ?></div>
            <p>本当に退会してよろしいですか？</p>
            <div class="btn-container">
              <input type="submit" name="submit" value="退会する" class="btn btn-center" />
            </div>
          </form>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>