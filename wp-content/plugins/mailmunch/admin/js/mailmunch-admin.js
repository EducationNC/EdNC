(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-specific JavaScript source
	 * should reside in this file.
	 *
	 * Note that this assume you're going to use jQuery, so it prepares
	 * the $ function reference to be used within the scope of this
	 * function.
	 *
	 * From here, you're able to define handlers for when the DOM is
	 * ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * Or when the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and so on.
	 *
	 * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
	 * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
	 * be doing this, we should try to minimize doing that in our own work.
	 */

	$(function() {
    $('.delete-widget').click(function() {
      if (!confirm('Are you sure you want to delete this optin form?')) return false;
      $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {action: 'delete_widget', widget_id: $(this).data('widget-id')},
        dataType: 'json',
        success: function(data) {
          if (data.success) {
            $(this).parents('tr').slideUp();
          }
          else {
            alert('There was an error. Please try again later.');
          }
        }.bind(this),
        error: function(data) {
          alert('There was an error. Please try again later.');
        }
      })
      return false;
    })

    $('#signup_form').submit(function(e) {
      e.preventDefault();

      var data = {
        email: $(this).find('input[name=email]').val(),
        password: $(this).find('input[name=password]').val(),
        site_name: $(this).find('input[name=site_name]').val(),
        site_url: $(this).find('input[name=site_url]').val(),
        action: 'sign_up',
      };

      $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function() {
          $('.signup-alert').hide();
        },
        success: function(data) {
          if (!data.success) {
            $('.signup-alert').html(data.message).show();
          } else {
            window.location.reload();
          }
        },
        error: function(data) {
          alert('There was an error. Please try again later.');
        }
      })
      return false;
    })

    $('#signin_form').submit(function(e) {
      e.preventDefault();

      var data = {
        email: $(this).find('input[name=email]').val(),
        password: $(this).find('input[name=password]').val(),
        action: 'sign_in',
      };

      $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function() {
          $('.signin-alert').hide();
        },
        success: function(data) {
          if (!data.success) {
            $('.signin-alert').html(data.message).show();
          } else {
          	window.location.reload();
          }
        },
        error: function(data) {
          alert('There was an error. Please try again later.');
        }
      })
      return false;
    })

	});

})( jQuery );

function showVideo() {
  document.getElementById('mailmunch-demo-video').innerHTML = '<iframe src="//player.vimeo.com/video/117103275?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" width="720" height="405" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
  document.getElementById('mailmunch-demo-video').style.display = 'block';
}

function hideVideo() {
  document.getElementById('mailmunch-demo-video').innerHTML = "";
  document.getElementById('mailmunch-demo-video').style.display = 'none';
}

window.onmessage = function (e) {
  if (e.data === 'refresh') {
    top.location.reload();
  }
};

function repositionSignupBox() {
  divId = 'signup-signin-box';
  var divWidth, divHeight;
  var objDiv = document.getElementById(divId);

  if (objDiv.clientWidth) {
    divWidth = objDiv.clientWidth;
    divHeight = objDiv.clientHeight;
  }
  else if (objDiv.offsetWidth)
  {
    divWidth = objDiv.offsetWidth;
    divHeight = objDiv.offsetHeight;
  }

  // Get the x and y coordinates of the center in output browser's window 
  var centerX, centerY;
  if (window.innerHeight)
  {
    centerX = window.innerWidth;
    centerY = window.innerHeight;
  }
  else if (document.documentElement && document.documentElement.clientHeight)
  {
    centerX = document.documentElement.clientWidth;
    centerY = document.documentElement.clientHeight;
  }
  else if (document.body)
  {
    centerX = document.body.clientWidth;
    centerY = document.body.clientHeight;
  }

  var offsetLeft = (centerX - divWidth) / 2;
  var offsetTop = (centerY - divHeight) / 2;

  objDiv.style.top = offsetTop + 'px';
  objDiv.style.left = offsetLeft + 'px';
}

function showSignInForm() {
  document.getElementById("sign-up-form").style.display = 'none';
  document.getElementById("sign-in-form").style.display = 'block';
  document.getElementById('why-account').style.display = 'none';
  showSignupBox();
}

function showSignUpForm() {
  document.getElementById("sign-in-form").style.display = 'none';
  document.getElementById("sign-up-form").style.display = 'block';
  document.getElementById('why-account').style.display = 'none';
  showSignupBox();
}

function showSignupBox(width, height) {
  document.getElementById("signup-signin-box-overlay").style.display = 'block';
  document.getElementById("signup-signin-box").style.display = 'block';
  repositionSignupBox();

  return false;
}

function hideSignupBox() {
  document.getElementById("signup-signin-box-overlay").style.display = 'none';
  document.getElementById("signup-signin-box").style.display = 'none';
}

function showWhyAccount() {
  document.getElementById('why-account').style.display = 'block';
  repositionSignupBox();
}
