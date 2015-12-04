function parallax($img){
  var $imgCont = $img.parent(),
      contHeight = $imgCont.height(),
      imgHeight = $img.height(),
      offsetTop = $imgCont.offset().top,
      windowHeight = $(window).height(),
      scrollTop = $(window).scrollTop(),
      position = Math.round(((contHeight + scrollTop) - offsetTop) * 0.5);

  // Adjust for admin bar
  if ($('body').hasClass('admin-bar')) {
    position += (32 * 0.5);
  }

  // only run parallax if in view
  if (offsetTop + imgHeight <= scrollTop || offsetTop >= scrollTop + windowHeight) {
    return;
  }

  $img.css({'transform':'translate3d(0px,' + position + 'px, 0px)'});
}
