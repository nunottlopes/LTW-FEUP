<div id="view_post">

    <div id="post_page_post">
        <article class="post_complete">
            <header>Posted by Amadeu 4 hours ago</header>
            <h1>Title</h1>
            <p>Husbands ask repeated resolved but laughter debating. She end cordial visitor noisier fat subject general picture. Or if offering confined entrance no. Nay rapturous him see something residence. Highly talked do so vulgar. Her use behaved spirits and natural attempt say feeling. Exquisite mr incommode immediate he something ourselves it of. Law conduct yet chiefly beloved examine village proceed. </p>

            <footer>
                <button class="post_button" onclick="upvote()"><i class='fas fa-arrow-up'></i> Upvote</button>
                <button class="post_button" onclick="downvote()"><i class='fas fa-arrow-down'></i> Downvote</button>
                <button class="post_button" onclick="save()"><i class="fa fa-bookmark"></i> Save</button>
                <button class="post_button" onclick="share()"><i class="fa fa-share-alt"></i> Share</button>
            </footer>
        </article>

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

        <section id="post_comments">
            <article class="post_comment">
                <header><a href="profile.php">Username</a> 14 day ago</header>
                <p>Comment here! Merry alone do it burst me songs. Sorry equal charm joy her those folly ham. In they no is many both. Recommend new contented intention improving bed performed age. Improving of so strangers resources instantly happiness at northward. Danger nearer length oppose really add now either. But ask regret eat branch fat garden. Become am he except wishes. Past so at door we walk want such sang. Feeling colonel get her garrets own.</p>
                <footer>
                    <button class="comment_button" onclick="upvote()"><i class='fas fa-arrow-up'></i> Upvote</button>
                    <button class="comment_button" onclick="downvote()"><i class='fas fa-arrow-down'></i> Downvote</button>
                    <button class="comment_button" onclick="reply()"><i class="fa fa-comment"></i> Reply</button>
                    <button class="comment_button" onclick="save()"><i class="fa fa-bookmark"></i> Save</button>
                </footer>
            </article>

            <article class="post_comment">
                <header><a href="profile.php">Username</a> 14 day ago</header>
                <p>Comment here! Merry alone do it burst me songs. Sorry equal charm joy her those folly ham. In they no is many both. Recommend new contented intention improving bed performed age. Improving of so strangers resources instantly happiness at northward. Danger nearer length oppose really add now either. But ask regret eat branch fat garden. Become am he except wishes. Past so at door we walk want such sang. Feeling colonel get her garrets own.</p>
                <footer>
                    <button class="comment_button" onclick="upvote()"><i class='fas fa-arrow-up'></i> Upvote</button>
                    <button class="comment_button" onclick="downvote()"><i class='fas fa-arrow-down'></i> Downvote</button>
                    <button class="comment_button" onclick="reply()"><i class="fa fa-comment"></i> Reply</button>
                    <button class="comment_button" onclick="save()"><i class="fa fa-bookmark"></i> Save</button>
                </footer>
            </article>

            <article class="post_comment">
                <header><a href="profile.php">Username</a> 14 day ago</header>
                <p>Comment here! Merry alone do it burst me songs. Sorry equal charm joy her those folly ham. In they no is many both. Recommend new contented intention improving bed performed age. Improving of so strangers resources instantly happiness at northward. Danger nearer length oppose really add now either. But ask regret eat branch fat garden. Become am he except wishes. Past so at door we walk want such sang. Feeling colonel get her garrets own.</p>
                <footer>
                    <button class="comment_button" onclick="upvote()"><i class='fas fa-arrow-up'></i> Upvote</button>
                    <button class="comment_button" onclick="downvote()"><i class='fas fa-arrow-down'></i> Downvote</button>
                    <button class="comment_button" onclick="reply()"><i class="fa fa-comment"></i> Reply</button>
                    <button class="comment_button" onclick="save()"><i class="fa fa-bookmark"></i> Save</button>
                </footer>
            </article>
            
        </section>
    </div>

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