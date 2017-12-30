<?php require_once "includes/config.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Блог IT Минималиста!</title>

  <!-- Bootstrap Grid -->
  <link rel="stylesheet" type="text/css" href="/media/assets/bootstrap-grid-only/css/grid12.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

  <!-- Custom -->
  <link rel="stylesheet" type="text/css" href="/media/css/style.css">
</head>
<body>

  <div id="wrapper">

    <?php include 'includes/header.php'; ?>

    <?php
      $article = mysqli_query($connection, "SELECT * FROM `articles` WHERE `id` = " . (int) $_GET['id']);
      if(mysqli_num_rows($article) <= 0) {
        ?>
        <div id="content">
          <div class="container">
            <div class="row">
              <section class="content__left col-md-8">
                <div class="block">
                  <h3>Статья не найдена</h3>
                  <div class="block__content">
                    <div class="full-text">
                      Запрашиваемая Вами статья не существует!
                    </div>
                  </div>
                </div>

              </section>
              <section class="content__right col-md-4">
                <?php include '/includes/sidebar.php' ?>
              </section>
            </div>
          </div>
        </div>
        <?php
      } else {
        $art = mysqli_fetch_assoc($article);
        mysqli_query($connection, "UPDATE `articles` SET `views` = `views` + 1 WHERE `id` = " . (int) $art['id']);
        ?>
        <div id="content">
          <div class="container">
            <div class="row">
              <section class="content__left col-md-8">
                <div class="block">
                  <a><?php echo $art['views']; ?> просмотров</a>
                  <h3><?php echo $art['title']; ?></h3>
                  <div class="block__content">
                    <img src="/static/images/<?php echo $art['image']; ?>" style="max-width:100%; display: block; margin: 0 auto 20px;">
                    <div class="full-text"><?php echo $art['text']; ?></div>
                  </div>
                </div>

<!---------------------------- форма отправки комментария ---------------------------->
                <div id="comment-add-form" class="block comment-add-form--hide">
                  <h3>Добавить комментарий</h3>
                  <div class="block__content">
                    <form class="form" method="POST" action="/article.php?id=<?php echo $art['id']; ?>#comment-add-form">
                      <?php
                      if(isset($_POST['do_post']))
                      {
                        $errors = array();

                        if( $_POST['name'] == '')
                        {
                          $errors[] = 'Введите имя!';
                        }

                        if( $_POST['nickname'] == '')
                        {
                          $errors[] = 'Введите никнейм!';
                        }

                        if( $_POST['email'] == '')
                        {
                          $errors[] = 'Введите email!';
                        }

                        if( $_POST['text'] == '')
                        {
                          $errors[] = 'Введите комментарий!';
                        }
                        if (empty($errors)) {
                          // добавить комментарий
                          mysqli_query($connection, "INSERT INTO `comments` (`author`,`nickname`, `email`,`text`,`pubdate`,`article_id`) VALUES ('".$_POST['name']."', '".$_POST['nickname']."', '".$_POST['email']."', '".$_POST['text']."', CURRENT_TIMESTAMP, '".$art['id']."')");

                          echo '<span style="color:green; font-weight:bold; display:inline-block; margin-bottom:10px;">Комментарий добавлен</span>';

                      } else {
                        // вывести ошибку
                        echo  '<span style="color:red; font-weight:bold; display:inline-block; margin-bottom:10px;">' .$errors['0'] .'</span>';
                        // echo $errors['0'];
                      }
                    }

                      ?>
                      <div class="form__group form__group--personal">
                        <div class="col-md-4 col-sm-12">
                          <input class="form__control" type="text" name="name" placeholder="имя" value="<?php echo $_POST['name']; ?>">
                        </div>
                        <div class="col-md-4 col-xs-12">
                          <input class="form__control" type="text" name="nickname" value="<?php echo $_POST['nickname']; ?>" placeholder="никнейм">
                        </div>
                        <div class="col-md-4 col-xs-12">
                          <input class="form__control" type="text" name="email" placeholder="email (не будет показан)" value="<?php echo $_POST['email']; ?>">
                        </div>
                      </div>
                      <div class="form__group">
                        <textarea class="form__control" name="text" placeholder="Напишите то что думаете..."><?php echo $_POST['text']; ?></textarea>
                      </div>
                      <div class="form__group">
                        <input class="form__control" type="submit" name="do_post" value="добавить комментарий">
                      </div>
                    </form>
                </div>
                </div>

<!------------------------------ коментарии ------------------------------>
        <div class="block">
          <a id="show-form-comment" href="#comment-add-form">Добавить свой</a>
          <h3>Комментарии</h3>
          <hr>
          <div class="block__content">
            <div class="articles articles__vertical">
              <?php
                $comments = mysqli_query($connection, "SELECT * FROM `comments` WHERE `article_id` = " . (int) $art['id'] . " ORDER BY `id` DESC");
                if(mysqli_num_rows($comments) <= 0) {
                  echo "нет комментариев";
                }
               while ($comment = mysqli_fetch_assoc($comments))
               {
                 ?>
                 <article class="article">
                   <div class="article__image" style="background-image: url(https://www.gravatar.com/avatar/<?php echo md5($comment['email']); ?>?s=125);"></div>
                   <div class="article__info">
                     <a href="/article.php?id=<?php echo $comment['article_id']; ?>"><?php echo $comment['author']; ?></a>
                     <div class="article__info__meta"></div>
                     <div class="article__info__preview"><?php echo $comment['text']; ?></div>
                   </div>
                 </article>
                 <?php
               }
               ?>

            </div>
          </div>
        </div>


              </section>
              <section class="content__right col-md-4">
                <?php include 'includes/sidebar.php' ?>
              </section>
            </div>
          </div>
        </div>
        <?php
      }
    ?>
    <?php include "includes/footer.php"; ?>

  </div>

  <script src="media/js/main.js"></script>
</body>
</html>
