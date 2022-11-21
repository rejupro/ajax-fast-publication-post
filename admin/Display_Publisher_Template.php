<div class="wrap">
    <div class="publisher_wrap">
        <h2>Publisher Options</h2>
        <span class="insert_success"></span>
        <span class="insert_error"></span>
        <form action="" autocomplete="off" id="submit_publisher">
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
        <form action="" autocomplete="off" id="update_publisher">
            <div class="publisher_form">
                <div class="form_single">
                    <input type="hidden" val="" id="update_id">
                    <label for="name">Publisher Name</label>
                    <input type="text" class="widefat" name="name_update" id="name_update" >
                    <span class="publish_error" id="name_error2"></span>
                </div>
                <div class="form_single">
                    <label for="email">Publisher Email</label>
                    <input type="text" class="widefat" name="email_update" id="email_update" >
                    <span class="publish_error" id="email_error2"></span>
                </div>
                <div class="form_button form_button2">
                    <a class="button button-warning button-large" id="cancel">Cancel</a>
                    <button class="button button-primary button-large" id="update">Update</button>
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
                    <?php
                        global $wpdb;
                        $table = $wpdb->prefix.'fastpublication_publisher';
                        $datas = $wpdb->get_results ( "SELECT * FROM $table ORDER BY id DESC");
                        $i = 1;
                        foreach($datas as $single) :
                    ?>
                        <tr>
                            <td><?php echo $i++ ; ?></td>
                            <td><?php echo $single->name ; ?></td>
                            <td><?php echo $single->email ; ?></td>
                            <td><a href="#" class="button button-warning button-large editPublisher" this-id="<?php echo $single->id; ?>">Edit</a></td>
                        </tr>

                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>