<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('パスワード変更');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');
// DBからユーザー情報を取得
$user_id = $_SESSION['user_id'];
$dbUserData = getUser($user_id);
debug('ユーザー情報：'.print_r($dbUserData, true));

//================================
// 画面表示処理
//================================
// POST送信がある場合
if (!empty($_POST)) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));

  // 変数定義
  $pass_old = $_POST['pass_old'];
  $pass_new = $_POST['pass_new'];
  $pass_new_re = $_POST['pass_new_re'];

  // 未入力チェック
  validRequired($pass_old,'pass_old');
  validRequired($pass_new,'pass_new');
  validRequired($pass_new_re,'pass_new_re');

  if (empty($err_msg)) {

    // バリデーション
    validPass($pass_old,'pass_old');
    validPass($pass_new,'pass_new');
    validPass($pass_new_re,'pass_new_re');

    validMatch($pass_new, $pass_new_re,'pass_new');

    // バリデーションを通った場合
    if (empty($err_msg)) {
      debug('DBと照合します');
      // 既存のパスワードと照合
      if (password_verify($pass_old, $dbUserData['password'])) {
        debug('DBと一致しました');

        // 古いパスワードと新しいパスワードの一致チェック
        if ($pass_old === $pass_new) {
          debug('現在のパスワードと同じです');
          $err_msg['pass_new'] = MSG12;
        }

        if (empty($err_msg)) {
          debug('バリデーションOK');

          debug('DBを更新します');
          try {
            // DB接続
            $dbh = dbConnect();
            // SQL作成
            $sql = 'UPDATE Users SET password = :pass WHERE id = :u_id';
            $data = array(
              ':pass' => password_hash($pass_new, PASSWORD_DEFAULT),
              ':u_id' => $user_id,
            );
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            if ($stmt) {
              debug('パスワード変更成功');
              $_SESSION['suc_msg'] = SUC02;
              header("Location:mypage.php");

            } else {
              debug('パスワード変更失敗');
              $err_msg['common'] = MSG08;
            }

          } catch (Exception $e) {
            debug('エラー：'.$e->getMessage());
            $err_msg['common'] = MSG08;
          }
        }

      } else {
        debug('DBと不一致');
        $err_msg['pass_old'] = MSG13;
      }
    }
  }
}

if(!empty($err_msg)) debug('エラー：'.print_r($err_msg,true));
debug('<<<<<画面表示処理終了<<<<<');

?>

<?php
$siteTitle = 'パスワード変更';
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
        <div class="form-container middle-form">
          <form action="" method="post">
            <div class="msg-area"><?php echo getErrMsg('common'); ?></div>
            <label>
              <p>現在のパスワード</p>
              <input type="password" name="pass_old" value="" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('pass_old'); ?></div>
            <label>
              <p>新しいパスワード</p>
              <input type="password" name="pass_new" value="" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('pass_new'); ?></div>
            <label>
              <p>新しいパスワード（再入力）</p>
              <input type="password" name="pass_new_re" value="" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('pass_new_re'); ?></div>

            <div class="btn-container">
              <input type="submit" value="変更する" class="btn btn-mid" />
            </div>
          </form>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>