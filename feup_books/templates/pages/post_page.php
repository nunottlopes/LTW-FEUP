<div id="view_post" class="page">

    <div id="post_page_post" story-id="<?=$_GET['id']?>">

        <article class="post_complete"></article>

        <div id="add_comment"></div>

        <div class="default_dropdown">
            <header>Sort by</header>
            <div id="dropdown_selection" selectionid="best">BEST</div>
            <div class="triangle_down"></div>
            <div id="dropdown_options" class="default-dropdown-content">
                <div id="top">TOP</div>
                <div id="bot">BOT</div>
                <div id="new">NEW</div>
                <div id="old">OLD</div>
                <div id="best">BEST</div>
                <div id="controversial">CONTROVERSIAL</div>
                <div id="average">AVERAGE</div>
                <div id="hot">HOT</div>
            </div>
        </div>
        <script src="javascript/dropdown.js" defer></script>

        <section id="post_comments"></section>
    </div>

    <script src="javascript/pages/post_page.js"></script>
    <script src="javascript/post_buttons.js"></script>

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
