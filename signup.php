<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('ユーザー登録');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');

debugLogStart();

//================================
// 画面表示処理
//================================
// POST送信がある場合
if (!empty($_POST)) {
  debug('POST送信あり');
  debug('POST'.print_r($_POST, true));

  // 変数定義
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  // 未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');

  if (empty($err_msg)) {
    // Emailのバリデーション
    validEmailForm($email, 'email');
    validMaxLen($email, 'email');
    validMinLen($email, 'email');
    validEmailDup($email, 'email');

    // パスワードのバリデーション
    validMaxLen($pass, 'pass');
    validMinLen($pass, 'pass');

    // 再入力パスワードのバリデーション
    validMaxLen($pass_re, 'pass_re');
    validMinLen($pass_re, 'pass_re');
    validMatch($pass,$pass_re,'pass');

    // バリデーションを通った場合
    if(empty($err_msg)) {
      debug('バリデーションOK');

      try {
        //DB接続
        $dbh = dbConnect();
        // SQL作成
        $sql = 'INSERT INTO Users (email, password, login_time, created_at) VALUES (:email, :pass, :login_time, :created_at)';
        $data = array(
          ':email' => $email,
          ':pass' => password_hash($pass,PASSWORD_DEFAULT),
          ':login_time' => date('Y-m-d H:i:s'),
          ':created_at' => date('Y-m-d H:i:s'),
        );
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
          debug('登録OK');
          // ユーザーIDをセッションへ保存
          $_SESSION['user_id'] = $dbh->lastInsertID();
          // ログイン日時を現在時で設定
          $_SESSION['login_date'] = time();
          // ログイン期限の設定（デフォルト30分）
          $sessionLimit = 60 * 30;
          $_SESSION['login_limit'] = $sessionLimit;

          debug('マイページへ遷移');
          header("Location:mypage.php");
        } else {
          debug('登録NG');
        }

      } catch (\Exception $e) {
        debug('エラー：'.getMessage());
        $err_msg['common'] = MSG08;
      }
    }
  }
}
if(!empty($err_msg)) debug('エラー：'.print_r($err_msg,true));
debug('<<<<<画面表示処理終了<<<<<');

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      href="https://fonts.googleapis.com/css?family=Kosugi+Maru&display=swap&subset=japanese"
      rel="stylesheet"
    />
    <link
      href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
      rel="stylesheet"
    />
    <title>わたしのご飯</title>
  </head>

  <body>
    <!-- ヘッダー -->
    <header>
      <div class="header-container">
        <div class="header-title">
          <img src="img/食事アイコン.png" alt="食事アイコン" />
        </div>
        <nav class="header-nav">
          <ul>
            <li><a href="index.php">トップページ</a></li>
            <li><a href="signup.php">ユーザー登録</a></li>
            <li><a href="login.php">ログイン</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <!-- メイン -->
    <main id="SIGNUP" class="layout-1-column">
      <h1 class="page-title">ユーザー登録</h1>
      <!-- メインコンテンツ -->
      <section>
        <div class="form-container large-form">
          <form action="" method="post">
            <div class="msg-area"><?php echo getErrMsg('common'); ?></div>
            <label for="">
              <p>Email</p>
              <input type="text" name="email" value="<?php echo FormData('email'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('email'); ?>
</div>
            <label for="">
              <p>
                パスワード<span style="font-size: 14px;">※英数字6文字以上</span>
              </p>
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('pass'); ?></div>
            <label for="">
              <p>パスワード（再入力）</p>
              <input type="password" name="pass_re" value="<?php echo FormData('pass_re'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('pass_re'); ?></div>
            <div class="btn-container">
              <input type="submit" value="登録" class="btn btn-mid" />
            </div>
          </form>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <footer>
      <p>
        &copy Copyright 2020 <a href="">わたしのご飯</a> All rights reserved.
      </p>
    </footer>

    <script src="js/vendor/jquery-3.4.1.min.js"></script>
  </body>
</html>
