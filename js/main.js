$(function() {
  // 浮き上がりフェードイン
  function animation() {
    $(".fadeInUp").each(function() {
      // ターゲット位置を取得
      var target = $(this).offset().top;
      // スクロール量を取得
      var scroll = $(window).scrollTop();
      // ウィンドウの高さを取得
      var windowHeight = $(window).height();
      // ターゲットまでスクロールするとフェードイン
      if (scroll > target - windowHeight) {
        $(this).css("opacity", "1");
        $(this).css("transform", "translateY(0)");
      }
    });
  }
  animation();
  $(window).scroll(function() {
    animation();
  });
  // メッセージ表示
  var $showMsg = $("#js-show-msg");
  var msg = $showMsg.text();
  if (msg.length) {
    $showMsg.slideToggle("slow");
    setTimeout(function() {
      $showMsg.slideToggle("slow");
    }, 3000);
  }
  // お気に入り登録・解除
  // 変数定義
  var $favorite, favoriteProductId;
  $favorite = $(".js-click-favorite") || null;
  favoriteProductId = $favorite.data("productid") || null;

  if (favoriteProductId !== undefined && favoriteProductId !== null) {
    $favorite.on("click", function() {
      var $this = $(this);
      $.ajax({
        type: "POST",
        url: "ajaxFavorite.php",
        data: { productId: favoriteProductId }
      })
        .done(function(data) {
          console.log("Ajax Success");
          $this.toggleClass("active");
        })
        .fail(function() {
          console.log("Ajax Error");
        });
    });
  }
  // 料理削除
  var $delete = $(".js-click-delete") || null;
  deleteProductId = $delete.data("productid") || null;

  if (deleteProductId !== undefined && deleteProductId !== null) {
    $delete.on("click", function() {
      var $this = $(this);
      var deleteFlg = confirm("本当に削除しますか？");
      if (deleteFlg) {
        console.log("削除します");
        $.ajax({
          url: "ajaxDelete.php",
          type: "POST",
          data: {
            productId: deleteProductId
          }
        })
          .done(function(data) {
            console.log("Ajax Success");
            // window.location.herf = "mypage.php";
            location.reload();
          })
          .fail(function() {
            console.log("Ajax Error");
          });
      } else {
        console.log("削除しません");
      }
    });
  }
});
