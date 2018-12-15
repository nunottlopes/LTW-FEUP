<?php include('templates/common/sort_dropdown.php') ?>

<div id="main_page" class="page">

    <div id="main_page_posts"></div>
    
    <div id="main_page_aside">
        <div id="aside_create_post" class="aside_div">
            <h1>Create Post</h1>
            <a href="create_post.php"><button type="button">CREATE POST</button></a>
            <a href="create_channel.php"><button type="button">CREATE CHANNEL</button></a>
        </div>

        <div id="aside_channels" class="aside_div">
            <header>CHANNELS</header>
            <ul></ul>
        </div>

        <div id="aside_favorite_post" class="aside_div">
            <header>FAVORITE POSTS</header>
            <ul><ul>
        </div>

        <div id="aside_footer" class="aside_div">
            <?php include('templates/common/footer.php') ?>
        </div>

    </div>

    <script src="javascript/pages/main_page.js"></script>
    <script src="javascript/post_buttons.js"></script>
</div>