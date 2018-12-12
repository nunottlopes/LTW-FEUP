<div id="create_channel" class="page">

    <div class="main_page_posts">
        <h1>Create a Channel</h1>
        <div id="new_channel_post">
            <form>
                <input type="text" name="channel_title" placeholder="Title"/>
                <textarea name="channel_description" placeholder="Description"></textarea>
                <input type="submit" name="channel_submission" value="Create"/>
            </form>
        </div>
    </div>

    <script src="javascript/pages/create_channel.js"></script>


    <div id="post_page_aside">
        <div id="post_page_rules" class="aside_div">
            <h1>Creating Channel Rules</h1>
            <ul>
                <li>You can not change the name of your channel after creating it</li>
                <li>You can not delete your subreddit after creating it</li>
            </ul>
        </div>

        <div id="aside_footer" class="aside_div">
            <?php include('templates/common/footer.php') ?>
        </div>

    </div>
</div>