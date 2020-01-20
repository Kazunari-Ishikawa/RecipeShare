<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('トップページ');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

?>

<?php
$siteTitle = 'トップページ';
require('head.php');
?>

  <body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メイン -->
    <!-- トップ画面 -->
    <section id="ABOUT">
      <div class="top-img">
        <img src="img/top-pic4M.jpg" alt="" />
        <div class="top-img-container">
          <h1>わたしのご飯</h1>
          <p>
            作ったご飯を登録しておくことで<br />今日のレシピに迷ったときにさっと検索できます
          </p>
          <div class="top-img-btn">
            <a href="signup.php" class="btn btn-sample1">登録する</a>
          </div>
        </div>
      </div>
    </section>

    <!-- コンテンツ -->
    <div class="container">
      <section id="DESCRIPTION">
        <h2>できること</h2>
        <div class="panel-container">
          <div class="panel description">
            <div class="panel-header">
              <h3>写真でかんたん登録</h3>
            </div>
            <div class="panel-main">
              <i class="fas fa-camera icon-middle"></i>
              <div class="panel-txt">
                <p>写真と料理のカテゴリだけでかんたんに登録できます</p>
              </div>
            </div>
          </div>
          <div class="panel description">
            <div class="panel-header">
              <h3>何を食べたか記録にも</h3>
            </div>
            <div class="panel-main">
              <i class="far fa-edit icon-middle"></i>
              <div class="panel-txt">
                <p>料理のカテゴリ、日付などで検索できます</p>
              </div>
            </div>
          </div>
          <div class="panel description">
            <div class="panel-header">
              <h3>レシピを検索できる</h3>
            </div>
            <div class="panel-main">
              <i class="fab fa-google icon-middle"></i>
              <div class="panel-txt">
                <p>レシピ検索ボタンを押すだけでGoogle検索できます</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- フッター -->
    <?php require('footer.php'); ?>