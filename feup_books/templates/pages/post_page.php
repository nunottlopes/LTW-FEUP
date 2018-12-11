<div id="view_post">

    <div id="post_page_post" story-id="<?php echo $_GET['id']?>">

        <article class="post_complete"></article>

        <div id="add_comment">
            <img src="images/users/user.png">
            <form action="#" method="post">
                <textarea name="comment" placeholder="Add your comment here..."></textarea>
                <input type="submit" value="Add comment" class="submit_comment_button">
            </form>
        </div>

        <div class="sort_by">
            <div id="sortby">Sort by</div>
            <div id="typesortby">BEST</div>
            <div class="triangle_down"></div>
            <div id="sort-dropdown" class="sort-dropdown-content">
                <a href="">BEST</a>
                <a href="">TOP</a>
                <a href="">NEW</a>
                <a href="">OLD</a>
            </div>
        </div>

        <section id="post_comments"></section>
    </div>

    <script src="javascript/pages/post_page.js"></script>
    <script src="javascript/post_buttons.js"></script>
    <script src="javascript/post_page.js"></script>

    <div id="post_page_aside">
        <div id="channel_subscription" class="aside_div">
            <h1>Channel</h1>
            <h2>123123 Subscribers</h1>
            <a href="#"><button type="button">Subscribe</button></a>
        </div>

        <div id="aside_footer" class="aside_div">
            <?php include('templates/common/footer.php') ?>
        </div>

    </div>
</div>