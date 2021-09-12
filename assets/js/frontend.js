(function ($, window) {
    'use strict';
    $('.user-actions').on('click',function(){
       $(this).parents('tr').hide();
    });
})(jQuery, window);