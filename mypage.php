<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('マイページ');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

// プロダクト全取得関数
function getMyProduct($user_id) {
  debug('全プロダクトを取得します');
  debug('ユーザーID：'.print_r($user_id, true));

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT * FROM Recipe WHERE user_id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $user_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // データ取得
    $result = $stmt->fetchAll();

    if ($stmt) {
      return $result;
    } else {
      return false;
    }

  } catch(Exception $e) {
    debug('エラー：'.$e->getMessage());
    $err_msg['common'] = MSG08;
  }
}

// 自分のプロダクトを取得
$user_id = $_SESSION['user_id'];
$viewData = getMyProduct($user_id);

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
          <form action="" method="post">
            <p>カテゴリ</p>
            <select name="category">
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