(function( $ ) {
	'use strict';

	var page = 2;
    $('body').on('click', '.fast_load_more', function(){
		$.ajax({
			type: 'post',
			url: publicationLoad.ajaxurl,
			data: {
				action: 'fast_publication_loadmore',
				page: page,
                security: publicationLoad.security
			},
            beforeSend: function(){
                $('.fast_load_more').text('Load More Publications...');
            },
			success:function(data){
                $('.fast_load_more').text('Load More Publications');
                if($.trim(data) != ''){
				    $('.fast-publication-gridpost').append(data);
                    page++
                }else{
                    $('.fast_load_more').hide(); 
                }    
			}
		});
	})


})( jQuery );
