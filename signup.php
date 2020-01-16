<?php
// 共通関数の読み込み
// require('function.php');

// debugLogStart();

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
            <li><a href="signup.html">ユーザー登録</a></li>
            <li><a href="login.html">ログイン</a></li>
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
            <div class="msg-area"></div>
            <label for="">
              <p>Email</p>
              <input type="text" name="email" value="" />
            </label>
            <div class="msg-area"></div>
            <label for="">
              <p>
                パスワード<span style="font-size: 14px;">※英数字6文字以上</span>
              </p>
              <input type="password" name="pass" value="" />
            </label>
            <div class="msg-area"></div>
            <label for="">
              <p>パスワード（再入力）</p>
              <input type="password" name="pass_re" value="" />
            </label>
            <div class="msg-area"></div>
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
