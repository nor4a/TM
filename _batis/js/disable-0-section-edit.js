require(['jquery'], function($) {
$(document).ready(function () {

  //course page
  $('#section-0').each(function () {
    $(this).find('.editing_title, .editing_move').remove();
    $(this).find('.addresourcemodchooser').remove();
    $(this).find('b.caret').remove();
    $(this).find('div.moodle-actionmenu.section-cm-edit-actions').each(function () {
      $('#' + $(this).attr('id') + '-menubar').find('a.toggle-display')
                                              .attr('href', $('#' + $(this).attr('id') + '-menu').find('a.editing_update').attr('href'))
                                              .click(function () { window.location.href = $(this).attr('href'); });
      $('#' + $(this).attr('id') + '-menu').remove();
    });
    $(this).find('li.modtype_url span.actions').remove();
  });

  //mod page
  $('form[action="modedit.php"] #id_name').prop('readonly', true).css('background-color', '#ddd');
  $('form[action="modedit.php"] fieldset.collapsed').hide();
  $('form[action="modedit.php"] #id_content').hide();


});

  });
