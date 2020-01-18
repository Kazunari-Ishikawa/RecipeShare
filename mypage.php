<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('マイページ');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

//================================
// 画面表示用データ取得
//================================
// GETパラメータ取得
debug('GET：'.print_r($_GET,true));
// カテゴリ
$c_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
// カレントページ
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;

// 1ページあたりの表示件数
$listNum = 6;
// 現在ページに表示する先頭レコードを算出
$currentMinNum = $listNum * ($currentPageNum-1);
// DBから一覧データを取得
$viewData = getProductList($_SESSION['user_id'], $currentMinNum,$listNum, $c_id);

// カテゴリデータ取得
$dbCategoryData = getCategory();

?>

<?php
$siteTitle = 'マイページ';
require('head.php');
?>

  <body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メイン -->
    <main id="mypage" class="layout-2-column">
      <h1 class="page-title"><?php echo $siteTitle; ?></h1>
      <div class="regist-container">
        <p><a href="registProduct.php">新しく料理を登録する</a></p>
      </div>
      <!-- サイドバー -->
      <section id="side-bar" class="layout-2-column-left">
        <div class="search-bar">
          <form action="" method="get">
            <p>カテゴリ</p>
            <select name="c_id">
              <option value="0">選択してください</option>
              <?php
                foreach ($dbCategoryData as $key => $val) {
              ?>
                  <option value="<?php echo $val['id']; ?>" <?php if (getFormData('category_id') == $val['id']) echo 'selected'; ?>><?php echo $val['name']; ?></option>
              <?php
                }
              ?>
            </select>
            <div class="btn-container">
              <input type="submit" value="検索" class="btn btn-center" />
            </div>
          </form>
        </div>
      </section>

      <!-- メインコンテンツ -->
      <section id="contents" class="layout-2-column-right">
        <div class="contents-container">
          <div class="card-container">
            <?php
              if (!empty($viewData)) {
                foreach ($viewData as $key => $val) {
            ?>
                <div class="card">
                  <a href="productDetail.php?p_id=<?php echo $val['id']; ?>">
                    <img src="<?php echo $val['pic']; ?>" alt="" />
                    <p><?php echo $val['date'] ?></p>
                    <p><?php echo $val['main_name']; ?></p>
                  </a>
                </div>
            <?php
                }
              } else {
            ?>
                <div class="card">
                  <a href="">
                    <img src="" alt="" />
                    <p></p>
                    <p></p>
                  </a>
                </div>
            <?php
              }
            ?>

          </div>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>