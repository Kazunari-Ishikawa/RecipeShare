<?php
// 共通関数の読み込み
require('function.php');

debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debug('プロフィール編集');
debug('◇◆◇◆◇◆◇◆◇◆◇◆◇◆◇');
debugLogStart();

// ログイン認証
require('auth.php');

// DBからユーザー情報を取得
$user_id = $_SESSION['user_id'];

debug('ユーザー情報を取得します');
debug('ユーザーID：'.print_r($user_id, true));

try {
  // DB接続
  $dbh = dbConnect();
  // SQL作成
  $sql = 'SELECT name, sex, age, email FROM Users WHERE id = :u_id AND delete_flg = 0';
  $data = array(':u_id' => $user_id);
  // クエリ実行
  $stmt = queryPost($dbh, $sql, $data);
  // データ取得
  $result = $stmt->fetch();
  debug('クエリ結果：'.print_r($result, true));

} catch(Exception $e) {
  debug('エラー：'.$e->getMessage());
  $err_msg['common'] = MSG08;
}

$dbFormData = $result;

//================================
// 画面表示処理
//================================
// POST送信がある場合
if (!empty($_POST)) {
  debug('POST送信あり');
  debug('POST：'.print_r($_POST, true));
  // 変数定義
  $name = $_POST['username'];
  $sex = (isset($_POST['sex'])) ? $_POST['sex'] : '';
  $age = (isset($_POST['age'])) ? $_POST['age'] : '';
  $email = $_POST['email'];

  // DBとのデータが異なる場合バリデーションを行う
  // 名前
  if ($name !== $dbFormData['name']) {
    validMaxLen($name, 'username');
  }
  // 性別のバリデーション
  if ($sex !== $dbFormData['sex']) {
    // male or female以外の値が送られてきた場合
    if ($sex !== 0) {
    } elseif ($sex !== 1) {
    } else {
      global $err_msg;
      $err_msg['sex'] = '不正な値です';
    }
  }
  // 年齢のバリデーション
  if ($age !== $dbFormData['age']) {
    validMaxLen($age, 'age');
    validHalf($age, 'age');
  }
  // Emailのバリデーション
  if ($email !== $dbFormData['email']) {
    validRequired($email, 'email');
    validEmailForm($email, 'email');
    validMaxLen($email, 'email');
    validMinLen($email, 'email');
    validEmailDup($email, 'email');
  }

  // バリデーションが通った場合
  if (empty($err_msg)) {
    debug('バリデーションOK');

    debug('DBを更新します');
    try {
      // DB接続
      $dbh = dbConnect();
      // SQL作成
      $sql = 'UPDATE Users SET name = :name, sex = :sex, age = :age, email = :email WHERE id = :u_id';
      $data = array(
        ':name' => $name,
        ':sex' => $sex,
        ':age' => $age,
        ':email' => $email,
        ':u_id' => $user_id,
      );
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      if ($stmt) {
        debug('DB更新成功');
        $_SESSION['suc_msg'] = 'プロフィールを更新しました';
        header("Location:mypage.php");
      } else {
        debug('DB更新失敗');
      }

    } catch(Exception $e) {
      debug('エラー：'.$e->getMessage());
      $err_msg['common'] = MSG08;
    }

  } else {
    debug('バリデーションNG');
  }

}

if(!empty($err_msg)) debug('エラー：'.print_r($err_msg,true));
debug('<<<<<画面表示処理終了<<<<<');

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
              <input type="radio" name="sex" value="0" checked/>男性
              <input type="radio" name="sex" value="1" />女性
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