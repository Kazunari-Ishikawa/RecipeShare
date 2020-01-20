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
});
