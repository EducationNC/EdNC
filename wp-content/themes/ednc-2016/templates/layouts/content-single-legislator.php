<?php while (have_posts()) : the_post(); ?>
  <div <?php post_class('container'); ?>>
    <div class="page-header">
      <div class="row">
        <div class="col-md-12">
          <h1 class="entry-title"><?php the_field('title'); ?> <?php the_title(); ?></h1>
          <?php
          $leadership = get_field('leadership');
          if ($leadership) {
            ?>
            <h2><?php echo $leadership; ?></h2>
            <?php
          }
          ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-4 col-md-3">
        <div class="background-steel text-center">
          <?php the_post_thumbnail('medium'); ?>
        </div>
      </div>
      <div class="col-sm-8 col-md-9">
        <div class="entry-content row">
          <div class="col-sm-6 col-md-4">
            <?php $party = get_field('party'); ?>
            <div class="callout <?php echo $party; ?>">
              <h6>Party</h6>
              <p class="h2"><?php echo $party; ?></p>
            </div>

            <div class="callout">
              <h6>Office</h6>
              <p><?php the_field('office'); ?></p>
              <h6>Phone</h6>
              <p><?php the_field('phone'); ?></p>
              <h6 class="overflow-ellipsis">Email</h6>
              <p><?php $email = get_field('email'); ?><a href="mailto: <?php echo antispambot($email); ?>" target="_blank"><?php echo antispambot($email); ?></a></p>
            </div>

            <a class="button btn-default btn-wide bottom-margin" href="<?php the_field('webpage'); ?>" target="_blank">Go to NCGA profile <span class="icon-external-link"></span></a>
          </div>

          <div class="col-sm-6 col-md-3">
            <div class="callout">
              <h6>District</h6>
              <p class="h1"><span class="big"><?php the_field('district'); ?></span></p>
              <p class="caption"><?php the_field('counties_in_district'); ?></p>
            </div>

            <div class="callout">
              <h6>Seat</h6>
              <p class="h1"><span class="big"><?php the_field('seat'); ?></span></p>
            </div>

            <div class="callout">
              <h6>Total legislative terms</h6>
              <p class="h1"><span class="big"><?php the_field('terms'); ?></span></p>
              <p class="caption small"><?php the_field('notes_about_terms'); ?></p>
            </div>
          </div>

          <div class="col-sm-12 col-md-5">
            <div class="callout">
              <h6>Home county</h6>
              <p class="h2"><?php the_field('home_county'); ?></p>
            </div>

            <div class="callout">
              <h6>Education committees</h6>
              <ul>
                <?php
                $ed_approp_house = get_field('education_appropriations');
                $ed_house = get_field('education_k-12');
                $ed_approp_senate = get_field('appropriations_ed_higher_ed');
                $ed_senate = get_field('ed_higher_ed');

                if ($ed_approp_house) { ?>
                  <li><a href="/house-education-appropriations-committee/">Appropriations Subcommittee on Education</a>, <?php echo $ed_approp_house; ?></li>
                  <?php
                }

                if ($ed_house) { ?>
                  <li><a href="/house-education-k-12-committee/">Education K-12</a>, <?php echo $ed_house; ?></li>
                  <?php
                }

                if ($ed_approp_senate) { ?>
                  <li><a href="/senate-appropriations-on-education-higher-education-committee/">Appropriations Subcommittee on Education/Higher Education</a>, <?php echo $ed_approp_senate; ?></li>
                  <?php
                }

                if ($ed_senate) { ?>
                  <li><a href="/senate-education-higher-education-committee/">Education/Higher Education</a>, <?php echo $ed_senate; ?></li>
                  <?php
                }
                ?>
              </ul>
            </div>

            <div class="callout">
              <h6>Occupation</h6>
              <p class="h2"><?php the_field('occupation'); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endwhile; ?>
