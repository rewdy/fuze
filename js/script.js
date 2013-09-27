jQuery(function(){
  // Message behavior
  jQuery('.messages').append('<a href="#close" class="closer">Close</a>');
  jQuery('.messages .closer').click(function(){
    jQuery(this).parent().slideUp(1000,function(){
      jQuery(this).remove();
      return false;
    });
  });
});