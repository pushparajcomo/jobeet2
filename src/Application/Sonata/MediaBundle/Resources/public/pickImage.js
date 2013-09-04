/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var imgobj = '';
jQuery(document).ready(function() {

    $("a[data-toggle=modal]").click(function() {
        var url = $(this).attr('href');
        var BlockDivId = $(this).parents('.cms-block').attr('id');
        imgobj = BlockDivId;
        var blockId = BlockDivId.substr(10,BlockDivId.length);
        loadMediaList(url,blockId);
    });

    function loadMediaList(url,blockId){
        $.ajax({
            url: url,
        }).done(function(data) {
             $('#myModal_'+blockId).html(data);
             $('.sonata-ba-filter').remove();
             $('.sonata-ba-list-field-header-batch').remove();
             $('.sonata-ba-list-field-batch').remove();
             $('.sonata-ba-list-field-string img').bind('click', function(e){
                $('#myModal_'+blockId).modal('hide');
                e.preventDefault();
                var path = $(this).attr('src');
                var row = $(this).parents('td'); 
                var mediaid = $(row).attr('objectid');
                var request = jQuery.ajax({
                            url: '/app_dev.php/admin/sonata/page/page/blockMediaSave',
                            type: "POST",
                            data: {
                                    mediaId : mediaid,
                                    blockId : blockId,
                            },
                            dataType: "html"
                    }).done(function(){
                        path = path.replace('admin','default_big');
                        $('#'+imgobj+' img').attr('src',path);                        
                        return false;
                    });

             });
        });

    }

//setTimeout(function(){alert('aaa'+$("#awesome-iframe").contents().find("img").length)},10000);


});
