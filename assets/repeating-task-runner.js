jQuery(document).on('ready', function () {
  // Re-submit the form every two seconds if selected
  if (jQuery('input#repeating-task-runner-continue').is(':checked')) {
    window.autoExecute = setTimeout(function () {
      jQuery('form#repeating-task-runner').submit();
    }, 2000);
  }
});

// Pause on click
jQuery('input#repeating-task-runer-pause').on('click', function () {
  // Stop auto-execution
  clearTimeout(window.autoExecute);

  // Alert the user
  alert('Auto-execution was successfully stopped.');

  jQuery(this).delay(100).fadeOut();
});
