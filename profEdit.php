<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('プロフィール編集');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

?>

<?php
$siteTitle = 'プロフィール編集';
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
              <p>名前</p>
              <input type="text" name="username" value="" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('username'); ?></div>
            <label>
              <p>性別</p>
              <input type="radio" name="sex" value="male" />男性
              <input type="radio" name="sex" value="female" />女性
            </label>
            <div class="msg-area"><?php echo getErrMsg('sex'); ?></div>
            <label>
              <p>年齢</p>
              <input type="text" name="age" value="" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('age'); ?></div>
            <label>
              <p>Email</p>
              <input type="text" name="email" value="" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('email'); ?></div>

            <div class="btn-container">
              <input type="submit" value="変更する" class="btn btn-mid" />
            </div>
          </form>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>