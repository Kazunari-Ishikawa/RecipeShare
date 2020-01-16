<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('ご飯登録');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

?>

<?php
$siteTitle = 'ご飯登録';
require('head.php');
?>

  <body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メイン -->
    <main id="SIGNUP" class="layout-1-column">
      <h1 class="page-title">ご飯を登録</h1>
      <!-- メインコンテンツ -->
      <section>
        <div class="form-container large-form">
          <form action="" method="post">
            <div class="msg-area"></div>
            <label for="">
              <p>カテゴリ</p>
              <input type="text" name="category" value="" />
            </label>
            <div class="msg-area"></div>
            <label for="">
              <p>主菜</p>
              <input type="text" name="main-name" value="" />
            </label>
            <div class="msg-area"></div>
            <label for="">
              <p>副菜</p>
              <input type="text" name="sub-name" value="" />
            </label>
            <div class="msg-area"></div>
            <label for="">
              <p>コメント</p>
              <input type="text" name="main-name" value="" />
            </label>
            <div class="msg-area"></div>
            <label for="">
              <p>写真</p>
              <input type="text" name="pic" value="" />
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
    <?php require('footer.php'); ?>