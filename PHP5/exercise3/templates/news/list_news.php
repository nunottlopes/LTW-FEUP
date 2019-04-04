<section id="news">
      <?php foreach($articles as $article) { ?>
        <article>
          <header>
            <h1><a href="news_item.php?id=<?=$article['id']?>"><?=$article['title']?></a></h1>
          </header>
          <img src="http://lorempixel.com/600/300/business/" alt="">
          <p><?=$article['introduction']?></p>
          <p><?=$article['fulltext']?></p>
          <footer>
            <span class="author"><?=$article['username']?></span>
            <span class="tags">
                <?php
                $fulltags = explode(',', $article['tags']);
                foreach($fulltags as $tag) {
                    echo "<a href='index.html'>#$tag</a> ";
                }
                ?>
            </span>
            <span class="date"><?=date('Y-m-d',$article['published'])?></span>
            <a class="comments" href="news_item.php?id=<?=$article['id']?>"><?=getNumComments($article['id'])?></a>
          </footer>
        </article>
      <?php } ?>
    </section>