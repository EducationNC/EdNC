<?php
/**
 * The template for the home page
 *
 * @package EducationNC
 */
?>

<?php get_template_part('partials/header'); ?>

            <section class="section">
              <div class="row">
                <div class="large-9 columns">
                  <div class="row">
                    <div class="medium-6 columns">
                      <div class="post">
                        <div class="photo-overlay small-wide">
                          <span class="label on-photo">Category</span>
                          <h3 class="post-title title-overlay">Lorem ipsum dolor sit amet, consectetur adipiscing elit</h3>
                          <a class="mega-link" href="#"></a>
                          <div class="overlay"></div>
                          <img src="https://placeimg.com/450/450/people" />
                        </div>

                        <div class="excerpt">
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin massa nibh, mollis quis laoreet a, vehicula tristique sem. Praesent aliquam ante a eros lobortis consectetur. Mauris in finibus massa. Aliquam a sem a urna cursus tempor ac nec eros. Integer nisl ligula, pretium sed felis quis, congue ullamcorper nulla. Vivamus in mi dui. In auctor bibendum ipsum at mattis.</p>
                          <a href="#" class="read-more tiny secondary button">Read more</a>
                        </div>
                      </div>
                    </div>

                    <div class="medium-6 columns">
                      <div class="post">
                        <div class="photo-overlay small-wide">
                          <span class="label on-photo">Category</span>
                          <h3 class="post-title title-overlay">Lorem ipsum dolor sit amet, consectetur adipiscing elit</h3>
                          <a class="mega-link"></a>
                          <div class="overlay"></div>
                          <img src="https://placeimg.com/450/450/tech" />
                        </div>

                        <div class="excerpt">
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin massa nibh, mollis quis laoreet a, vehicula tristique sem. Praesent aliquam ante a eros lobortis consectetur. Mauris in finibus massa. Aliquam a sem a urna cursus tempor ac nec eros. Integer nisl ligula, pretium sed felis quis, congue ullamcorper nulla. Vivamus in mi dui. In auctor bibendum ipsum at mattis.</p>
                          <a href="#" class="read-more tiny secondary button">Read more</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="large-3 columns">
                  <div class="row">
                    <div class="medium-6 large-12 columns">
                      <div class="callout">
                        <p>Hi there. We're new here. We'd like to make this website a part of your day. What features are important to you that you'd like to see here?</p>
                        <a class="button">Send us your thoughts</a>
                      </div>
                    </div>
                    <div class="underwriting medium-6 large-12 columns">
                      <img src="http://placehold.it/350x350" />
                      <p class="label"><a href="#">EdNC thanks our sponsors</a></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="large-3 columns">
                  <div class="post row">
                    <a class="small-3 large-12 columns" href="#">
                      <img src="https://placeimg.com/350/350/any?1" />
                    </a>
                    <div class="small-9 large-12 columns">
                      <span class="label">Category</span>
                      <h4 class="post-title"><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit</a></h4>
                      <p class="meta">by <a href="#">Author Name</a> on <date>11/7/2014</date></p>
                    </div>
                  </div>
                </div>

                <div class="large-3 columns">
                  <div class="post row">
                    <a class="small-3 large-12 columns" href="#">
                      <img src="https://placeimg.com/350/350/any?2" />
                    </a>
                    <div class="small-9 large-12 columns">
                      <span class="label">Category</span>
                      <h4 class="post-title"><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit</a></h4>
                      <p class="meta">by <a href="#">Author Name</a> on <date>11/7/2014</date></p>
                    </div>
                  </div>
                </div>

                <div class="large-3 columns">
                  <div class="post row">
                    <a class="small-3 large-12 columns" href="#">
                      <img src="https://placeimg.com/350/350/any?3" />
                    </a>
                    <div class="small-9 large-12 columns">
                      <span class="label">Category</span>
                      <h4 class="post-title"><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit</a></h4>
                      <p class="meta">by <a href="#">Author Name</a> on <date>11/7/2014</date></p>
                    </div>
                  </div>
                </div>

                <div class="large-3 columns">
                  <div class="post row">
                    <a class="small-3 large-12 columns" href="#">
                      <img src="https://placeimg.com/350/350/any?4" />
                    </a>
                    <div class="small-9 large-12 columns">
                      <span class="label">Category</span>
                      <h4 class="post-title"><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit</a></h4>
                      <p class="meta">by <a href="#">Author Name</a> on <date>11/7/2014</date></p>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <section class="section">
              <div class="full-width collapse">
                <div class="large-6 columns">
                  <img src="https://placeimg.com/650/290/nature" />
                </div>

                <div class="large-6 columns">
                  <img src="https://placeimg.com/650/290/arch" />
                </div>
              </div>
            </section>

            <section class="section">
              <div class="row">
                <div class="large-4 columns">
                  <h3>EdNews</h3>
                  <div ng-controller="example">
                    <ul>
                      <li ng-repeat="post in postdata">{{post.title}}</li>
                    </ul>
                  </div>
                </div>

                <div class="large-4 columns">
                  <h3>EdEvents</h3>
                  <div ng-controller="example">
                    <ul>
                      <li ng-repeat="post in postdata">{{post.title}}</li>
                    </ul>
                  </div>
                </div>

                <div class="large-4 columns">
                  <h3>EdTweets</h3>
                  <div ng-controller="example">
                    <ul>
                      <li ng-repeat="post in postdata">{{post.title}}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </section>

            <section class="section">
              <div class="full-width">
                <h2>"I'm a public school kid"</h2>
                <div class="photo-grid">
                  <div class="square">
                    <img src="http://placeimg.com/350/350/people?1" />
                  </div>
                  <div class="square hide-for-small-only">
                    <img src="http://placeimg.com/350/350/people?2" />
                  </div>
                  <div class="square hide-for-small-only">
                    <img src="http://placeimg.com/350/350/people?3" />
                  </div>
                  <div class="square hide-for-small-only">
                    <img src="http://placeimg.com/350/350/people?4" />
                  </div>
                  <div class="square hide-for-small-only">
                    <img src="http://placeimg.com/350/350/people?5" />
                  </div>
                </div>
              </div>
            </section>

            <section class="section">
              <div class="row">
                <div class="medium-4 columns">
                  <h3><strong>Poll:</strong> What issues are most important to you in the 2015-16 session of the NCGA?</h3>
                  <p>Pick your top 3:</p>
                  <form>
                    <label for="checkbox1"><input id="checkbox1" type="checkbox" />Issue</label>
                    <label for="checkbox2"><input id="checkbox2" type="checkbox" />Issue</label>
                    <label for="checkbox3"><input id="checkbox3" type="checkbox" />Issue</label>
                    <label for="checkbox4"><input id="checkbox4" type="checkbox" />Issue</label>
                    <label for="checkbox5"><input id="checkbox5" type="checkbox" />Issue</label>
                    <label for="checkbox6"><input id="checkbox6" type="checkbox" />Issue</label>
                    <label for="checkbox7"><input id="checkbox7" type="checkbox" />Issue</label>
                    <input type="submit" class="button" value="Make your voice heard" />
                  </form>
                </div>

                <div class="medium-4 columns">
                  <div class="photo-overlay small-wide">
                    <h3 class="title-overlay">Send us your success stories of public education in NC on Twitter and Instagram using the hashtag #edncsuccess and we may feature you on the site!</h3>
                    <a class="mega-link" href="#"></a>
                    <div class="overlay"></div>
                    <img src="https://placeimg.com/350/350/any?7" />
                  </div>
                </div>

                <div class="medium-4 columns">
                  <div class="callout">
                    <h3>Register for free digest</h3>
                    <p>Sign up to receive your choice of a daily news summary, breaking news alerts, or our newsletter.</p>
                    <form>
                      <input type="email" placeholder="Email address" />
                    </form>
                  </div>

                  <a class="button" href="#">Donate Now</a>
                </div>
              </div>
            </section>

<?php get_template_part('partials/footer'); ?>
