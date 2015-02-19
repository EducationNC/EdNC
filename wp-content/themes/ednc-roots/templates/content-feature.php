<?php
$category = get_the_category();
$column = wp_get_post_terms(get_the_id(), 'column');
?>

<div class="special-feature-bg">
  <div class="bg-img">
    <?php the_post_thumbnail(); ?>
  </div>
</div>
<div class="special-feature-content container">
  <div class="special-feature-intro col-md-4 hidden-sm hidden-xs">
    <?php echo get_field('longform_intro'); ?>
  </div>

  <article <?php post_class('special-feature col-md-7 col-md-push-1'); ?>>
    <header class="entry-header">
      <?php
      if ($column) {
        ?>
        <span class="label"><?php echo $column[0]->name; ?></span>
        <?php
      } else {
        if ($category[0]->cat_name != 'Uncategorized' && $category[0]->cat_name != 'Hide from home' && $category[0]->cat_name != 'Hide from archives') {
          ?>
          <span class="label">
            <?php if (in_category(109)) {  // 1868 Constitutional Convention ?>
            <a href="<?php echo get_category_link(109); ?>">
              <?php echo $category[0]->cat_name; ?>
            </a>
            <?php } else {
              echo $category[0]->cat_name;
            } ?>
          </span>
          <?php
        }
      }
      ?>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
      <?php get_template_part('templates/social', 'share'); ?>
    </header>

    <div class="entry-content">
      <div class="intro visible-sm visible-xs">
        <?php echo get_field('longform_intro'); ?>
      </div>

      <?php the_content(); ?>
    </div>

    <footer class="entry-footer">
      <h3>About <?php the_author(); ?></h3>
      <?php
      $author_id = get_the_author_meta('ID');
      $args = array(
        'post_type' => 'bio',
        'meta_query' => array(
          array(
            'key' => 'user',
            'value' => $author_id
          )
        )
      );

      $bio = new WP_Query($args);

      if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post(); ?>
        <?php the_post_thumbnail('full', array('class' => 'author-photo')); ?>
        <?php get_template_part('templates/author', 'excerpt'); ?>
      <?php endwhile; endif; wp_reset_query(); ?>
    </footer>
    <?php // comments_template('/templates/comments.php'); ?>
  </article>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
  (function() {
    // detect if IE : from http://stackoverflow.com/a/16657946
    var ie = (function(){
      var undef,rv = -1; // Return value assumes failure.
      var ua = window.navigator.userAgent;
      var msie = ua.indexOf('MSIE ');
      var trident = ua.indexOf('Trident/');

      if (msie > 0) {
        // IE 10 or older => return version number
        rv = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
      } else if (trident > 0) {
        // IE 11 (or newer) => return version number
        var rvNum = ua.indexOf('rv:');
        rv = parseInt(ua.substring(rvNum + 3, ua.indexOf('.', rvNum)), 10);
      }

      return ((rv > -1) ? rv : undef);
    }());


    // disable/enable scroll (mousewheel and keys) from http://stackoverflow.com/a/4770179
    // left: 37, up: 38, right: 39, down: 40,
    // spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
    var keys = [32, 37, 38, 39, 40], wheelIter = 0;

    function preventDefault(e) {
      e = e || window.event;
      if (e.preventDefault)
      e.preventDefault();
      e.returnValue = false;
    }

    function keydown(e) {
      for (var i = keys.length; i--;) {
        if (e.keyCode === keys[i]) {
          preventDefault(e);
          return;
        }
      }
    }

    function touchmove(e) {
      preventDefault(e);
    }

    function wheel(e) {
      // for IE
      //if( ie ) {
        //preventDefault(e);
      //}
    }

    function disable_scroll() {
      window.onmousewheel = document.onmousewheel = wheel;
      document.onkeydown = keydown;
      document.body.ontouchmove = touchmove;
    }

    function enable_scroll() {
      window.onmousewheel = document.onmousewheel = document.onkeydown = document.body.ontouchmove = null;
    }

    var docElem = window.document.documentElement,
      scrollVal,
      isRevealed,
      noscroll,
      isAnimating,
      container = document.getElementById( 'oc-pusher' ),
      trigger = container.querySelector( 'button.trigger' );  // not using this

    function scrollY() {
      return window.pageYOffset || docElem.scrollTop;
    }

    function scrollPage() {
      scrollVal = scrollY();

      if( noscroll && !ie ) {
        if( scrollVal < 0 ) return false;
        // keep it that way
        window.scrollTo( 0, 0 );
      }

      if( classie.has( container, 'notrans' ) ) {
        classie.remove( container, 'notrans' );
        return false;
      }

      if( isAnimating ) {
        return false;
      }

      if( scrollVal <= 0 && isRevealed ) {
        toggle(0);
      }
      else if( scrollVal > 0 && !isRevealed ){
        toggle(1);
      }
    }

    function toggle( reveal ) {
      isAnimating = true;

      if( reveal ) {
        classie.add( container, 'scrolled' );
      }
      else {
        noscroll = true;
        disable_scroll();
        classie.remove( container, 'scrolled' );
      }

      // simulating the end of the transition:
      setTimeout( function() {
        isRevealed = !isRevealed;
        isAnimating = false;
        if( reveal ) {
          noscroll = false;
          enable_scroll();
        }
      }, 600 );
    }

    // refreshing the page...
    var pageScroll = scrollY();
    noscroll = pageScroll === 0;

    disable_scroll();

    if( pageScroll ) {
      isRevealed = true;
      classie.add( container, 'notrans' );
      classie.add( container, 'scrolled' );
    }

    window.addEventListener( 'scroll', scrollPage );
    // trigger.addEventListener( 'click', function() { toggle( 'reveal' ); } );
  })();
});
</script>
