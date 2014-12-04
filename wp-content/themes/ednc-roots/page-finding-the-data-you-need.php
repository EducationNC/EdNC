<?php while (have_posts()) : the_post(); ?>

  <nav class="fixed-right">
    <a href="#section1" class="current">Introduction</a>
    <a href="#section2">Key Numbers</a>
    <a href="#section3">NC Context</a>
    <a href="#section4">International Sources</a>
    <a href="#section5">National Context</a>
    <a href="#section6">National Sources</a>
    <a href="#section7">State Sources</a>
    <a href="#section8">NC Organizations</a>
    <a href="#section9">Polling</a>
    <a href="#section10">The Future of Data</a>
  </nav>

  <section id="section1">
    <div class="page-header">
      <h1><?php echo roots_title(); ?></h1>
      <h2>Lions and Tigers and Bears, Oh My!</h2>
    </div>

    <?php the_content(); ?>
  </section>

  <section id="section2">
    
  </section>

<?php endwhile; ?>
