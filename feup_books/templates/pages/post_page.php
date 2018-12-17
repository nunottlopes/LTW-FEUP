<?php
$storyid = (int)$_GET['id'];
?>
<div id="view_post" class="page">

    <div id="post_page_post">

        <article class="post_complete"></article>

        <div id="add_comment"></div>

        <div class="default_dropdown selectable-dropdown">
            <header>Sort by</header>
            <div class="dropdown_selection" selectionid="best">BEST</div>
            <div class="triangle_down"></div>
            <div class="dropdown_options default-dropdown-content">
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
        <script src="javascript/utils/dropdown.js" defer></script>

        <section id="post_comments"></section>
    </div>

    <div id="post_page_aside">
        <div id="channel_info" class="aside_div">
            <h1></h1>
            <h2></h2>
            <p></p>
        </div>

        <div id="aside_footer" class="aside_div">
            <?php require_once __DIR__ . '/../common/footer.php' ?>
        </div>

    </div>

    <script type="text/javascript">
        const storyid = <?= $storyid ?>;
    </script>
    <script src="javascript/pages/post_page.js" defer></script>
    <script src="javascript/common/post_buttons.js" defer></script>

</div>
