<div class="wrap">
    <div class="publisher_wrap">
        <h2>Publisher Options</h2>
        <form action="" autocomplete="off" id="submit_publisher">
            <span class="insert_success"></span>
            <div class="publisher_form">
                <div class="form_single">
                    <label for="name">Publisher Name</label>
                    <input type="text" class="widefat" name-nonce="<?php echo wp_create_nonce('name_nonce'); ?>" name="name" id="name" >
                    <span class="publish_error" id="name_error"></span>
                </div>
                <div class="form_single">
                    <label for="email">Publisher Email</label>
                    <input type="text" class="widefat" email-nonce="<?php echo wp_create_nonce('email_nonce'); ?>" name="email" id="email" >
                    <span class="publish_error" id="email_error"></span>
                </div>
                <div class="form_button">
                    <button class="button button-primary button-large">Add New</button>
                </div>
            </div>
        </form>
        <div class="publisher_list">
            <h2>Publisher List</h2>
            <table class="wp-list-table widefat fixed striped table-view-list posts">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="show_publisher">
                    <tr>
                        <td>1</td>
                        <td>Rakib</td>
                        <td>rakib@gmail.com</td>
                        <td><a href="#" class="button button-warning button-large">Edit</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Shakib</td>
                        <td>shakib@gmail.com</td>
                        <td><a href="#" class="button button-warning button-large">Edit</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
</div>