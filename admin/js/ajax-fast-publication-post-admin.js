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
					if(data.message_error){
						$('.insert_error').text(data.message_error);
						$('.form_button button').text('Add New');
						setTimeout(function() {
							$('.insert_error').text('');
						}, 3000);
					}else{
						$('.form_button button').text('Add New');
						$('.insert_success').text(data.message);
						$('#submit_publisher').trigger("reset");
						setTimeout(function() {
							$('.insert_success').text('');
							location.reload();
						}, 2000);
					}
				}
			});
		}
		return false;
	})
	
	// Edit Data
	$('#update_publisher').hide();
	$('.editPublisher').on('click', function(){
		$('#submit_publisher').hide();
		$('#update_publisher').show();
		var editid = $(this).attr('this-id');

		$.ajax({
			type: 'post',
			url: editData.ajaxurl,
			data: {
				action: 'fast_publication_edit',
				editid: editid
			},
			success:function(data){
				$('#update_id').val(data.id);
				$('#name_update').val(data.name);
				$('#email_update').val(data.email);
			}
		});
	})

	$('#cancel').on('click', function(event){
		event.preventDefault();
		$('#update_publisher').hide();
		$('#submit_publisher').show();
	})
	
	// Update Data
	$('#update_publisher').on('submit', function(){
		var name = $('#name_update').val();
		var email = $('#email_update').val();
		var id = $('#update_id').val();
		
		if(name.length < 1 && email.length < 1){
			$('#name_error2').html('Please Insert Name');
			$('#email_error2').html('Please Insert Email');
		}else if(name.length < 1){
			$('#name_error2').html('Please Insert Name');
			$('#email_error2').html('');
		}else if(email.length < 1){
			$('#email_error2').html('Please Insert Email');
			$('#name_error2').html('');
		}else{
			$('#name_error2').html('');
			$('#email_error2').html('');
			$.ajax({
				type: 'post',
				url: updateData.ajaxurl,
				data: {
					action: 'fast_publication_update',
					name: name,
					email: email,
					id: id,
				},
				beforeSend: function(){
					$('.form_button2 button').text('Updating...');
				},
				success:function(data){
					if(data.message_error){
						$('.insert_error').text(data.message_error);
						$('.form_button2 button').text('Update');
						setTimeout(function() {
							$('.insert_error').text('');
						}, 3000);
					}else{
						$('.form_button2 button').text('Update');
						$('.insert_success').text(data.message);
						setTimeout(function() {
							$('.insert_success').text('');
							location.reload();
						}, 2000);
					}
					console.log(data);
				}
			});
		}
		return false;
	})



})( jQuery );
