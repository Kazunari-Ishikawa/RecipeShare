<header>
  <div class="header-container">
    <div class="header-title">
      <img src="img/食事アイコン.png" alt="食事アイコン" />
    </div>
    <nav class="header-nav">
      <ul>
        <?php if (empty($_SESSION['user_id'])){ ?>
          <li><a href="index.php">トップページ</a></li>
          <li><a href="signup.php">ユーザー登録</a></li>
          <li><a href="login.php">ログイン</a></li>
        <?php } else { ?>
          <li><a href="mypage.php">マイページ</a></li>
          <li><a href="favorite.php">お気に入り</a></li>
          <li><a href="profEdit.php">個人設定</a></li>
          <li><a href="logout.php">ログアウト</a></li>
        <?php } ?>
      </ul>
    </nav>
  </div>
</header>
