<?php while (have_posts()) : the_post(); ?>
  <div class="row">
    <div class="col-md-4">
      <?php the_post_thumbnail('full'); ?>
    </div>
    <div <?php post_class('col-md-8'); ?>>
      <header class="entry-header extra-bottom-margin">
        <h1 class="entry-title no-top-margin"><?php the_field('title'); ?> <?php the_title(); ?></h1>

        <?php
        $leadership = get_field('leadership');
        if ($leadership) {
          ?>
          <h2><?php echo $leadership; ?></h2>
          <?php
        }
        ?>
      </header>
      <div class="entry-content row">
        <div class="col-sm-4">
          <?php $party = get_field('party'); ?>
          <div class="callout <?php echo $party; ?>">
            <p>Party</p>
            <p class="h1"><?php echo $party; ?></p>
          </div>

          <div class="callout">
            <?php
            $ed_approp_house = get_field('education_appropriations');
            $ed_house = get_field('education_k-12');
            $ed_approp_senate = get_field('appropriations_ed_higher_ed');
            $ed_senate = get_field('ed_higher_ed');

            if ($ed_approp_house) { ?>
              <p class="normal-style"><em>Education Appropriations Committee</em><br /><?php echo $ed_approp_house; ?></p>
              <?php
            }

            if ($ed_house) { ?>
              <p class="normal-style"><em>Education K-12 Committee</em><br /><?php echo $ed_house; ?></p>
              <?php
            }

            if ($ed_approp_senate) { ?>
              <p class="normal-style"><em>Appropriations on Education/Higher Education Committee</em><br /><?php echo $ed_approp_senate; ?></a></p>
              <?php
            }

            if ($ed_senate) { ?>
              <p class="normal-style"><em>Education/Higher Education Committee</em><br /><?php echo $ed_senate; ?></a></p>
              <?php
            }
            ?>
          </div>

          <a class="button btn-default btn-wide" href="<?php the_field('webpage'); ?>" target="_blank">Go to NCGA profile <span class="icon-external-link"></span></a>
        </div>

        <div class="col-sm-3">
          <div class="callout">
            <p>District</p>
            <p class="h1"><span class="big"><?php the_field('district'); ?></span></p>
            <p class="caption small"><?php the_field('counties_in_district'); ?></p>
          </div>

          <div class="callout">
            <p>Terms in Senate and House</p>
            <p class="h1"><span class="big"><?php the_field('terms'); ?></span></p>
            <p class="caption small"><?php the_field('notes_about_terms'); ?></p>
          </div>
        </div>

        <div class="col-sm-5">
          <div class="callout">
            <p class="normal-style"><em>Office</em><br /><?php the_field('office'); ?></p>
            <p class="normal-style"><em>Phone</em><br /><?php the_field('phone'); ?></p>
            <p class="normal-style"><em>Email</em><br /><?php $email = get_field('email'); ?><a href="mailto: <?php echo antispambot($email); ?>" target="_blank"><?php echo antispambot($email); ?></a></p>
          </div>

          <div class="callout">
            <p>Occupation</p>
            <p class="h1"><?php the_field('occupation'); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endwhile; ?>
