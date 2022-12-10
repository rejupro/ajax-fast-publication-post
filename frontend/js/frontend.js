(function( $ ) {
	'use strict';
	// Publication Load More
	$('#fast_pubmonthfilter').hide();
    
    
    if($('.fast_load_more').attr('data-page') == 1){
        $('.site-footer').hide();
        $('.copyright').hide();
        $(window).scroll(function(e){

            var page = $('.fast_load_more').data('page');
            var newPage = page + 1;
    
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {
    
    
                $.ajax({
                    type: 'post',
                    url: publicationLoad.ajaxurl,
                    data: {
                        action: 'fast_publication_loadmore',
                        page: page,
                        security: publicationLoad.security
                    },
                    beforeSend: function(){
                        $('.fast_load_more').addClass('lds-hourglass2');
                        $('.fast_load_more').text('');
                        $('body').css({"overflow-y":"hidden", "margin-right":"16px"})
                    },
                    success:function(data){
                        $('body').css({"overflow-y":"scroll", "margin-right":"0px"})
                        $('.fast_load_more').removeClass('lds-hourglass2');
                        $('.fast_load_more').text('Load More Publications');
                        if($.trim(data) != ''){
                            $('.fast-publication-gridpost').append(data);
                            $('.fast_load_more').data('page', newPage);
                        }else{
                            $('.fast_load_more').hide(); 
                            $('.site-footer').show();
                            $('.copyright').show();
                        }    
                    }
                });
    
            }
        })
    }

    

    // $('body').on('click', '.fast_load_more', function(){
	// 	$.ajax({
	// 		type: 'post',
	// 		url: publicationLoad.ajaxurl,
	// 		data: {
	// 			action: 'fast_publication_loadmore',
	// 			page: page,
    //             security: publicationLoad.security
	// 		},
    //         beforeSend: function(){
    //             $('.fast_load_more').addClass('lds-hourglass2');
    //             $('.fast_load_more').text('');
    //         },
	// 		success:function(data){
    //             $('.fast_load_more').removeClass('lds-hourglass2');
    //             $('.fast_load_more').text('Load More Publications');
    //             if($.trim(data) != ''){
	// 			    $('.fast-publication-gridpost').append(data);
    //                 page++
    //             }else{
    //                 $('.fast_load_more').hide();
	// 				alert('You watched every publications'); 
    //             }    
	// 		}
	// 	});
	// })

	// Publication Search
	$('#fastajax-publicationform').on('submit', function(){
		return false;
	});
	$('#fastajax-publicationform #fastpublicationinput').on('keyup', function(){
        var searchcontent = $('#fastpublicationinput').val();
        var searchNonce = $(this).attr('data-nonce');
        
        $.ajax({
            type: 'post',
            url: publicationSearch.ajaxurl,
            data: {
                action: 'fast_ajax_pubsearch',
                search_data: searchcontent,
                searchNonce: searchNonce,
            },
            beforeSend: function(){
                $('.ajax-searchloading').addClass('lds-hourglass2');
            },
            success:function(data){
                if(searchcontent.length > 0){
                    $('.fast_allpublication').hide();
					$('.fast-loadmore-area').hide();
                }else{
                    $('.fast_allpublication').show();
					$('.fast-loadmore-area').show();
                }
                $('.ajax-searchloading').removeClass('lds-hourglass2');
                $('#pubsearchOutput').html(data);
            }
        });
	});	

	// Publication by Category
	$('#fast_pubcat').on('change', function(){
		var catin = $(this).val();
		$.ajax({
            type: 'post',
            url: publicationCategory.ajaxurl,
            data: {
                action: 'fast_ajax_pubcategory',
                catin: catin,
            },
            beforeSend: function(){
                $('.ajax-searchloading').addClass('lds-hourglass2');
            },
            success:function(data){
				$('.fast_allpublication').hide();
				$('.fast-loadmore-area').hide();
                $('.ajax-searchloading').removeClass('lds-hourglass2');
                $('#pubsearchOutput').html(data);
            }
        });
	})

	// Publication by Author 
	$('#fast_pubauthor').on('change', function(){
		var authorin = $(this).val();
		$.ajax({
            type: 'post',
            url: publicationCategory.ajaxurl,
            data: {
                action: 'fast_ajax_pubauthorin',
                authorin: authorin,
            },
            beforeSend: function(){
                $('.ajax-searchloading').addClass('lds-hourglass2');
            },
            success:function(data){
				$('.fast_allpublication').hide();
				$('.fast-loadmore-area').hide();
                $('.ajax-searchloading').removeClass('lds-hourglass2');
                $('#pubsearchOutput').html(data);
            }
        });
	})

	// Publication by Publisher 
	$('#fast_pubpublisher').on('change', function(){
		var publisherin = $(this).val();
		$.ajax({
            type: 'post',
            url: publicationPublisher.ajaxurl,
            data: {
                action: 'fast_ajax_publisherin',
                publisherin: publisherin,
            },
            beforeSend: function(){
                $('.ajax-searchloading').addClass('lds-hourglass2');
            },
            success:function(data){
				$('.fast_allpublication').hide();
				$('.fast-loadmore-area').hide();
                $('.ajax-searchloading').removeClass('lds-hourglass2');
                $('#pubsearchOutput').html(data);
            }
        });
	})

	// Publication by Year 
	$('#fast_pubyear').on('change', function(){
		$('#fast_pubmonthfilter').show();
		var publisheryearin = $(this).val();
		$.ajax({
            type: 'post',
            url: publicationYear.ajaxurl,
            data: {
                action: 'fast_ajax_yearin',
                publisheryearin: publisheryearin,
            },
            beforeSend: function(){
                $('.ajax-searchloading').addClass('lds-hourglass2');
            },
            success:function(data){
				$('.fast_allpublication').hide();
				$('.fast-loadmore-area').hide();
                $('.ajax-searchloading').removeClass('lds-hourglass2');
                $('#pubsearchOutput').html(data);
            }
        });
	})

	// Publication by Month 
	$('#fast_pubmonth').on('change', function(){
		var publisheryearin = $('#fast_pubyear').val();
		var publishermonthin = $(this).val();
		$.ajax({
            type: 'post',
            url: publicationMonth.ajaxurl,
            data: {
                action: 'fast_ajax_monthin',
				publisheryearin: publisheryearin,
                publishermonthin: publishermonthin,
            },
            beforeSend: function(){
                $('.ajax-searchloading').addClass('lds-hourglass2');
            },
            success:function(data){
				$('.fast_allpublication').hide();
				$('.fast-loadmore-area').hide();
                $('.ajax-searchloading').removeClass('lds-hourglass2');
                $('#pubsearchOutput').html(data);
            }
        });
	})


})( jQuery );
