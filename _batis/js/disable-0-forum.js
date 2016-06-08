require(['jquery'], function($) {
  $(document).ready(function () {
    //course page
    $('#section-0').each(function () {
      $('li.modtype_url span.instancename').parent('a').attr('target','_blank');
    });
  });
});
