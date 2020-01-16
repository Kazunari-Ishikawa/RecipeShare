<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('パスワード変更');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

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
            <div class="msg-area"></div>
            <label for="">
              <p>現在のパスワード</p>
              <input type="password" name="pass_old" value="" />
            </label>
            <div class="msg-area"></div>
            <label for="">
              <p>新しいパスワード</p>
              <input type="password" name="pass_new" value="" />
            </label>
            <div class="msg-area"></div>
            <label for="">
              <p>新しいパスワード（再入力）</p>
              <input type="password" name="pass_new_re" value="" />
            </label>
            <div class="msg-area"></div>

            <div class="btn-container">
              <input type="submit" value="変更する" class="btn btn-mid" />
            </div>
          </form>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>