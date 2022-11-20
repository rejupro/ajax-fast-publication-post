(function( $ ) {
	'use strict';

	$('#submit_publisher').on('submit', function(){
		var name = $('#name').val();
		var email = $('#email').val();
		var name_nonce = $('#name').attr('name-nonce');
		var email_nonce = $('#name').attr('email-nonce');


		if(name.length < 1 && email.length < 1){
			$('#name_error').html('Please Insert Name');
			$('#email_error').html('Please Insert Email');
		}else if(name.length < 1){
			$('#name_error').html('Please Insert Name');
			$('#email_error').html('');
		}else if(email.length < 1){
			$('#email_error').html('Please Insert Email');
			$('#name_error').html('');
		}else{
			$('#name_error').html('');
			$('#email_error').html('');
			$.ajax({
				type: 'post',
				url: publisherSubmit.ajaxurl,
				data: {
					action: 'fast_publication_submit',
					name: name,
					email: email,
					name_nonce: name_nonce,
					email_nonce: email_nonce,
				},
				beforeSend: function(){
					$('.form_button button').text('Add New...');
				},
				success:function(data){
					$('.form_button button').text('Add New');
					$('.insert_success').text(data.message);
					$('#submit_publisher').trigger("reset");
					// if(searchcontent.length > 0){
					// 	$('.blog_default').hide();
					// }else{
					// 	$('.blog_default').show();
					// }
					// $('.ajax-searchloading').removeClass('lds-hourglass');
					// $('#searchOutput').html(data);
					setTimeout(function() {
						$('.insert_success').text('');
					}, 3000);
					console.log(data);
				}
			});
		}
		return false;
	})

})( jQuery );
