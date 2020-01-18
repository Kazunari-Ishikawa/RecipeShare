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
});
