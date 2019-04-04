<section id="comments">
            <h1><?echo $numCommentsString?></h1>
            <?php foreach($comments as $comment) { ?>
                <article class="comment">
                    <span class="user"><?=$comment['username']?></span>
                    <span class="date"><?=date('Y-m-d',$article['published'])?></span>
                    <p><?=$comment['text']?></p>
        </article>
            <?php } ?>
        <form>
            <h2>Add your voice...</h2>
            <label>Username 
              <input type="text" name="username">
            </label>
            <label>E-mail
              <input type="email" name="email">
            </label>
            <label>Comment
              <textarea name="comment"></textarea>            
            </label>
            <input type="submit" value="Reply">
          </form>
        </section>