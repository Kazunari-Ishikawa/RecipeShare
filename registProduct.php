<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('ご飯登録');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

// DBからユーザー情報を取得
$user_id = $_SESSION['user_id'];

// DBからカテゴリデータを取得
// カテゴリデータ取得関数
function getCategory() {
  debug('カテゴリデータを取得します');

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL作成
    $sql = 'SELECT * FROM category WHERE delete_flg = 0';
    $data = array();
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
$dbCategoryData = getCategory();
// debug('カテゴリデータ：'.print_r($dbCategoryData, true));

//================================
// 画面表示処理
//================================
// POST送信がある場合
if (!empty($_POST)) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));
  debug('FILE：'.print_r($_FILES, true));

  // 変数定義
  // $category = $_POST['category'];
  $mainName = $_POST['main-name'];
  $subName = $_POST['sub-name'];
  $comment = $_POST['comment'];
  // $pic = $_POST['pic'];

  // 未入力チェック
  validRequired($mainName, 'main-name');

  // 各項目のバリデーション
  if (empty($err_msg)) {
    // カテゴリ

    // 主菜
    validMaxLen($mainName, 'main-name');

    // 副菜
    validMaxLen($subName, 'sub-name');

    // コメント
    validMaxLen($comment, 'comment');

    // 写真

    // バリデーションを通った場合
    if (empty($err_msg)) {
      debug('バリデーションOK');

      debug('DBへ登録します');
      try {
        // DB接続
        $dbh = dbConnect();
        // SQL作成
        $sql = 'INSERT INTO Recipe(category_id,main_name,sub_name,comment,pic,user_id,created_at) VALUES (:c_id,:main_name,:sub_name,:comment,:pic,:u_id,:created_at)';
        $data = array(
          // ':category_id' => $category,
          ':main_name' => $mainName,
          ':sub_name' => $subName,
          ':comment' => $comment,

          ':created_at' => date('Y-m-d H:i:s'),
        );
        // クエリ実行
        // $stmt = queryPost($dbh, $sql, $data);
        // if ($stmt) {
        //   debug('登録成功');
        //   $_SESSION['suc_msg'] = 'ご飯を登録しました';
        //   header("Location:mypage.php");
        // } else {
        //   debug('登録失敗');
        //   $err_msg['common'] = MSG08;
        // }









      } catch (Exception $e) {
        debug('エラー：'.$e->getMessage());
        $err_msg['common'] = MSG08;
      }

    } else {
      debug('バリデーションNG');
    }

  } else {
    debug('バリデーションNG');
  }
}

if(!empty($err_msg)) debug('エラー：'.print_r($err_msg,true));
debug('<<<<<画面表示処理終了<<<<<');

?>

<?php
$siteTitle = 'ご飯登録';
require('head.php');
?>

  <body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メイン -->
    <main id="REGISTER" class="layout-1-column">
      <h1 class="page-title">ご飯を登録</h1>
      <a href="registProduct.php">更新</a>
      <!-- メインコンテンツ -->
      <section>
        <div class="form-container large-form">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="msg-area"></div>
            <label>
              <p>カテゴリ</p>
              <select name='category'>
                <option value="0">選択してください</option>
                <?php
                  if (!empty($dbCategoryData)) {
                    foreach ($dbCategoryData as $key => $val) {
                ?>
                      <option value="<?php echo $val['id']; ?>" <?php if (!empty($_POST['category']) && $_POST['category'] == $val['id']) echo 'selected'; ?>><?php echo $val['name']; ?></option>
                <?php
                    }
                  }
                ?>
              </select>
            </label>
            <div class="msg-area"><?php echo getErrMsg('category'); ?></div>
            <label>
              <p>主菜</p>
              <input type="text" name="main-name" value="<?php echo getFormData('main-name'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('main-name'); ?></div>
            <label>
              <p>副菜</p>
              <input type="text" name="sub-name" value="<?php echo getFormData('sub-name'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('sub-name'); ?></div>
            <label>
              <p>コメント</p>
              <input type="text" name="comment" value="<?php echo getFormData('comment'); ?>" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('comment'); ?></div>
            <label>
              <p>写真</p>
              <input type="file" name="pic" accept="image/*" />
            </label>
            <div class="msg-area"><?php echo getErrMsg('pic'); ?></div>

            <div class="btn-container">
              <input type="submit" value="登録" class="btn btn-mid" />
            </div>
          </form>
        </div>
      </section>
    </main>

    <!-- フッター -->
    <?php require('footer.php'); ?>