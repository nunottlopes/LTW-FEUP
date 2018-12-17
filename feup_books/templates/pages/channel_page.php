<?php
require_once __DIR__ . '/../common/sort_dropdown.php';

$channelid = (int)$_GET['id'];
?>
<div id="channel_page" class="page">

    <div id="channel_page_posts"></div>
    
    <div id="channel_page_aside">
        <div id="channel_info" class="aside_div">
            <h1></h1>
            <h2></h2>
            <p></p>
        </div>

        <div id="aside_footer" class="aside_div">
            <?php require_once __DIR__ . '/../common/footer.php'; ?>
        </div>

    </div>

    <script type="text/javascript">
        const channel_id = <?= $channelid ?>;
    </script>
    <script src="javascript/pages/channel_page.js" defer></script>
    <script src="javascript/common/post_buttons.js" defer></script>
</div>