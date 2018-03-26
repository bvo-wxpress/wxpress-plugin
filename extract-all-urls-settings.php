<?php

require_once(plugin_dir_path(__FILE__) . 'functions.php');

function generate_html(){

    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    $custom_posts_names = array();
    $custom_posts_labels = array();
    $user_ids = array();
    $user_names = array();

    $args = array(
        'public'    => true,
        '_builtin'  => false
    );

    $output = 'objects';

    $operator = 'and';

    $post_types = get_post_types($args, $output, $operator);

    foreach($post_types as $post_type){

        $custom_posts_names[] = $post_type->name;
        $custom_posts_labels[] = $post_type->labels->singular_name;

    }

    $users = get_users();

    foreach ($users as $user) {
        $user_ids[] = $user->data->ID;
        $user_names[] = $user->data->user_login;
    }

?>

    <div class="wrap">

        <h2 align="center">Export Data from you Site</h2>

        <div id="WtiLikePostOptions" class="postbox">

            <div class="inside">

                <form id="infoForm" method="post">

                    <table class="form-table">

                        <tr>

                            <th>Select a Post Type to Extract Data:</th>

                            <td>

                                <label><input type="radio" name="post-type" value="any" required="required" checked /> All Types (pages, posts, and custom post types)</label><br/>
                                <label><input type="radio" name="post-type" value="page" required="required" /> Pages</label><br/>
                                <label><input type="radio" name="post-type" value="post" required="required" /> Posts</label><br/>

<?php

                                if(!empty($custom_posts_names) && !empty($custom_posts_labels)){
                                    for( $i = 0; $i < count($custom_posts_names); $i++ ){
                                        echo '<label><input type="radio" name="post-type" value="'. $custom_posts_names[$i] . '" required="required" /> ' . $custom_posts_labels[$i] . ' Posts</label><br>';
                                    }
                                }
?>

                            </td>

                        </tr>

                        <tr>

                            <th>Additional Data:</th>

                            <td>

                                <label><input type="checkbox" name="additional-data[]" value="url" checked /> URLs</label><br/>
                                <label><input type="checkbox" name="additional-data[]" value="title" /> Titles</label><br/>
                                <label><input type="checkbox" name="additional-data[]" value="category" /> Categories</label><br/>

                            </td>

                        </tr>

                        <tr>

                            <th>Post Status:</th>

                            <td>

                                <label><input type="radio" name="post-status" checked value="publish" /> Published</label><br/>
                                <label><input type="radio" name="post-status" value="pending" /> Pending</label><br/>
                                <label><input type="radio" name="post-status" value="draft" /> Draft & Auto Draft</label><br/>
                                <label><input type="radio" name="post-status" value="future" /> Future Scheduled</label><br/>
                                <label><input type="radio" name="post-status" value="private" /> Private</label><br/>
                                <label><input type="radio" name="post-status" value="trash" /> Trashed</label><br/>
                                <label><input type="radio" name="post-status" value="all" /> All (Published, Pending, Draft, Future Scheduled, Private & Trash)</label><br/>

                            </td>

                        </tr>

                        <tr>
                            <th></th>
                            <td><a href="#" id="advanceOptionsLabel" onclick="showAdvanceOptions(); return false;">Show Advance Options</a></td>
                        </tr>

                        <tr class="advance-options" style="display: none">

                            <th>By Author:</th>

                            <td>

                                <label><input type="radio" name="post-author" checked value="all" required="required" /> All</label><br/>
                                <?php

                                    if(!empty($user_ids) && !empty($user_names)){
                                        for( $i = 0; $i < count($user_ids); $i++ ){
                                            echo '<label><input type="radio" name="post-author" value="'. $user_ids[$i] . '" required="required" /> ' . $user_names[$i] . '</label><br>';
                                        }
                                    }
                                ?>

                            </td>

                        </tr>

                        <tr class="advance-options" style="display: none">

                            <th>Number of Posts: <a href="#" title="Specify Post Range to Extract, It is very useful in case of Memory Out Error!" onclick="return false" >?</a> </th>

                            <td>

                                <label><input type="radio" name="number-of-posts" checked value="all" required="required" onclick="hideRangeFields()" /> All</label><br/>
                                <label><input type="radio" name="number-of-posts" value="range" required="required" onclick="showRangeFields()" /> Specify Range</label><br/>

                                <div id="postRange" style="display: none">
                                    From: <input type="number" name="starting-point" placeholder="0" >
                                    To: <input type="number" name="ending-point" placeholder="500" >
                                </div>

                            </td>

                        </tr>

                        <tr>

                            <th>Export Type:</th>

                            <td>

                                <label><input type="radio" name="export-type" value="text" required="required" /> CSV</label><br/>
                                <label><input type="radio" name="export-type" value="here" required="required" /> Output here</label><br/>
                                <label><input checked type="radio" name="export-type" value="wxpress" required="required" /> WxPress</label><br/>

                            </td>

                        </tr>

                        <tr>

                            <td></td><td><input type="submit" name="export" class="button button-primary" value="Export Now"/></td>

                        </tr>

                    </table>


                </form>

            </div>

        </div>

        <h4 align="right">Developed by: <a href="http://AtlasGondal.com/?utm_source=self&utm_medium=wp&utm_campaign=plugin&utm_term=export-url" target="_blank">Atlas Gondal</a></h4>

        <script type="text/javascript">
            function showRangeFields(){
                document.getElementById('postRange').style.display ='block';
            }
            function hideRangeFields(){
                document.getElementById('postRange').style.display ='none';
            }
            function showAdvanceOptions() {

                var rows = document.getElementsByClassName('advance-options');

                for(var i = 0; i < rows.length; i++)
                {
                    rows[i].style.display = 'table-row';
                }

                document.getElementById('advanceOptionsLabel').innerHTML = "Hide Advance Options";
                document.getElementById('advanceOptionsLabel').setAttribute("onclick", "javascript: hideAdvanceOptions(); return false;");

            }
            function hideAdvanceOptions() {

                var rows = document.getElementsByClassName('advance-options');

                for(var i = 0; i < rows.length; i++)
                {
                    rows[i].style.display = 'none';
                }

                document.getElementById('advanceOptionsLabel').innerHTML = "Show Advance Options";
                document.getElementById('advanceOptionsLabel').setAttribute("onclick", "javascript: showAdvanceOptions(); return false;");

            }
        </script>


    </div>



<?php

    if (isset($_POST['export'])) {

        if (!empty($_POST['post-type']) && !empty($_POST['export-type']) && !empty($_POST['additional-data']) && !empty($_POST['post-status']) && !empty($_POST['post-author']) && !empty($_POST['number-of-posts']) ) {

            $post_type = $_POST['post-type'];
            $export_type = $_POST['export-type'];
            $additional_data = $_POST['additional-data'];
            $post_status = $_POST['post-status'];
            $post_author = $_POST['post-author'];
            $number_of_posts = $_POST['number-of-posts'];

            if($additional_data == ''){
                echo "Sorry, you missed export type, Please <strong>Select Export Type</strong> and try again! :)";
                exit;
            }

            if($number_of_posts == "range") {
                $offset = $_POST['starting-point'];
                $post_per_page = $_POST['ending-point'];

                if(!isset($offset) || !isset($post_per_page)) {
                    echo "Sorry, you didn't specify starting and ending post range. Please <strong>Set Post Range</strong> OR <strong>Select All</strong> and try again! :)";
                    exit;
                }

                $post_per_page = $post_per_page - $offset;


            } else {
                $offset = 'all';
                $post_per_page = 'all';
            }

            $selected_post_type = get_selected_post_type($post_type, $custom_posts_names);

            generate_output($selected_post_type, $post_status, $post_author, $post_per_page, $offset, $export_type, $additional_data);

        }

    }

}

generate_html();
