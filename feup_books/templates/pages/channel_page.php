<?php include('templates/common/sort_dropdown.php') ?>

<div id="channel_page" channel_id="<?= $_GET['id']?>" class="page">

    <div id="channel_page_posts"></div>
    
    <div id="channel_page_aside">
        <div id="channel_subscription" class="aside_div">
            <h1></h1>
            <h2></h2>
            <p></p>
            <a href="#"><button type="button">Subscribe</button></a>
        </div>

        <div id="aside_footer" class="aside_div">
            <?php include('templates/common/footer.php') ?>
        </div>

    </div>

    <script src="javascript/pages/channel_page.js"></script>
    <script src="javascript/post_buttons.js"></script>
</div>