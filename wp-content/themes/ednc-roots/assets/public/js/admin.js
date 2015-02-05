console.log('loaded');
jQuery(document).ready(function($) {
  $('a[href=#edit_update]').click(function() {
    $('#updatediv').slideToggle();
    $('a.edit-update').toggle();
  })
});
