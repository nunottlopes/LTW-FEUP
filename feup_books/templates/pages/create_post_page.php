<div id="create_post" class="page">

    <div id="create_post_main">
        <h1>Create a Post</h1>
        <div class="default_dropdown" id="channel_select">
            <header>Channel</header>
            <div id="dropdown_selection">Select a Channel</div>
            <div class="triangle_down"></div>
            <div id="dropdown_options" class="default-dropdown-content">
            </div>
        </div>

        <div class="tab">
            <button class="tablinks" onclick="tab_option(event, 'new_post_post')" id="tab_default">Post</button>
            <button class="tablinks" onclick="tab_option(event, 'new_post_image')">Image</button>
            <button class="tablinks" onclick="tab_option(event, 'new_post_title')">Title</button>
        </div>

        <div id="new_post_post" class="tabcontent">
            <form>
                <input type="text" name="post_title" placeholder="Title"/>
                <textarea name="post_text" placeholder="Text"></textarea>
                <input type="submit" name="post_submission" value="Post"/>
            </form>
        </div>

        <div id="new_post_image" class="tabcontent">
            <!-- <form enctype="multipart/form-data">
                <input type="text" name="post_title" placeholder="Title"/>
                <input type="file" name="post_image" accept="image/*"/>
                <input type="submit" name="post_submission" value="Post"/>
            </form> -->
            <form action="/feup_books/api/upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="upload-file" id="upload-file"/>
                <input type="submit" value="Upload Image" name="submit"/>
            </form>
        </div>

        <div id="new_post_title" class="tabcontent">
            <form>
                <input type="text" name="post_title" placeholder="Title"/>
                <input type="submit" name="post_submission" value="Post"/>
            </form>
        </div>
    </div>

    <script src="javascript/pages/create_post.js"></script>
    <script src="javascript/tab.js" defer></script>
    <script src="javascript/dropdown.js" defer></script>

    <div id="post_page_aside">
        <div id="post_page_rules" class="aside_div">
            <h1>Posting rules</h1>
            <ul>
                <li>Regras</li>
                <li>Why</li>
                <li>Not</li>
                <li>Regras</li>
            </ul>
        </div>

        <div id="aside_footer" class="aside_div">
            <?php include('templates/common/footer.php') ?>
        </div>

    </div>
</div>