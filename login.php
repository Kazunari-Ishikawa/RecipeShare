<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('ログイン');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');

debugLogStart();

//================================
// 画面表示処理
//================================
// POST送信がある場合
if (!empty($_POST)) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));

  // 変数定義
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save'])) ? true : false;

  // 未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');

  if (empty($err_msg)) {
    // Emailのバリデーション
    validEmailForm($email, 'email');
    validMaxLen($email, 'email');
    validMinLen($email, 'email');

    // パスワードのバリデーション
    validHalf($pass, 'pass');
    validMaxLen($pass, 'pass');
    validMinLen($pass, 'pass');

    // バリデーションを通った場合、パスワード照合
    if(empty($err_msg)) {
      debug('バリデーションOK');

      try {
        //DB接続
        $dbh = dbConnect();
        // SQL作成
        $sql = 'SELECT password, id FROM Users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // データ取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        debug('クエリ結果：'.print_r($result,true));

        // Emailの一致するデータが存在する場合パスワード照合
        if ($stmt && !empty($result['password'])) {
          if (password_verify($pass, $result['password'])) {
            debug('照合OK');

            // ユーザーIDをセッションへ保存
            $_SESSION['user_id'] = $result['id'];
            // ログイン日時を現在時で設定
            $_SESSION['login_date'] = time();

            // ログイン期限の設定（デフォルト30分）
            $sessionLimit = 60 * 30;
            // ログイン保持の場合
            if ($pass_save) {
              debug('ログイン保持チェックあり');
              $_SESSION['login_limit'] = 60 * 60 * 24 * 10;
            } else {
              debug('ログイン保持チェックなし');
              $_SESSION['login_limit'] = $sessionLimit;
            }

            debug('セッション変数：'.print_r($_SESSION,true));

            debug('マイページへ遷移');
            header("Location:mypage.php");

          } else {
            debug('パスワード照合NG');
            $err_msg['common'] = MSG09;
          }
        } else {
          debug('Eamil照合NG');
          $err_msg['common'] = MSG09;
        }

      } catch (Exception $e) {
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
    <title>ログイン</title>
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
    <main id="LOGIN" class="layout-1-column">
      <h1 class="page-title">ログイン</h1>
      <!-- メインコンテンツ -->
      <section>
        <div class="form-container large-form">
          <form action="" method="post">
            <div class="msg-area"><?php echo getErrMsg('common'); ?></div>
            <label for="">
              <p>Email</p>
              <input type="text" name="email" value="<?php echo getFormData('email'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('email'); ?></div>
            <label for="">
              <p>パスワード</p>
              <input type="password" name="pass" value="<?php echo getFormData('pass'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('pass'); ?></div>
            <label for="">
              <input type="checkbox" name="pass_save" /><span class="pass-save"
                >次回ログインを省略する</span
              >
            </label>
            <div class="btn-container">
              <input type="submit" value="ログイン" class="btn btn-mid" />
            </div>
            <p>パスワードを忘れた方は<a href="">こちら</a></p>
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
