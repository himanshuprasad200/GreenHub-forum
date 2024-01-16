<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>GreenHub - Social Media Platform for Eco-Friendly Living</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>
    <?php
       $id = $_GET['threadid'];
       $sql = "SELECT * FROM `threads` WHERE thread_id=$id"; 
       $result = mysqli_query($conn, $sql);
       while($row = mysqli_fetch_assoc($result)){
           $title = $row['thread_title'];
           $desc = $row['thread_desc'];
           $thread_user_id = $row['thread_user_id'];

           // Query the users table to find out the name of OP
           $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
           $result2 = mysqli_query($conn, $sql2);
           $row2 = mysqli_fetch_assoc($result2);
           $posted_by = $row2['user_email'];
    }
    
    ?>

    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if($method=='POST'){
        //Insert into comment db
        $comment = $_POST['comment'];
        $comment = str_replace("<", "&lt;", $comment);
        $comment = str_replace(">", "&gt;", $comment);
        $sno = $_POST['sno'];
        $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp())"; 
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if($showAlert){
            echo'
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your comment has been successfully posted!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
    ?>



    <!-- Category container starts here -->
    <div class="container my-4">
        <div class="jumbotron jumbotron-fluid bg-secondary">
            <div class="container">
                <h1 class="display-4 text-light"><?php echo $title;?></h1>
                <p class="lead text-light"><?php echo $desc;?></p>
                <hr class="my-4">
                <p>This is a peer to peer forum. No Spam / Advertising / Self-promote in the forums is not allowed. Do
                    not post copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross
                    post questions. Remain respectful of other members at all times.
                </p>
                <P>Posted by: <em><?php echo $posted_by; ?></em></p>
            </div>
        </div>

        <?php
        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
        echo '<div class="container">
            <h1 class="py-2">Post a comment</h1>
            <form action="' .$_SERVER["REQUEST_URI"] . '" method="post">
                <div class="form-floating">
                    <textarea class="form-control mb-4" placeholder="Leave a comment here" id="comment" name="comment"
                        style="height: 100px"></textarea>
                    <label for="floatingTextarea2">Type your comment</label>
                    <input type="hidden" name="sno" value="'. $_SESSION["sno"]. '">
                </div>
                <button type="submit" class="btn btn-success">Post Comments</button>
            </form>
        </div>';
        }
        else{
            echo '
 
                    <div class="container">
                        <h1 class="py-2">Post a comment</h1>
                        <p class="lead my-3"> You are not logged in. Please login to be able to post a comment</p>
                    </div>
                    ';
            }

 ?>

        <div class="container mb-5">
            <h1 class="py-2">Discussions</h1>
            <?php 
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `comments` WHERE thread_id=$id";
    $result = mysqli_query($conn, $sql);
    $noResult = true;
    while($row = mysqli_fetch_assoc($result)){
        $noResult = false;
        $id = $row['comment_id'];
        $content = $row['comment_content'];
        $comment_time = $row['comment_time'];
        $thread_user_id = $row['comment_by'];
        $sql2 = "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        
        
  
    
            echo '<div class="media my-3">
                <img src="img\userdefault.png" width="60px" class="mr-3" alt="...">
                <div class="media-body">
                <p class="text-dark my-0">'. $row2['user_email'] .' at '. $comment_time. '</p>   
                    '. $content . '
                </div>
            </div>';
        }
        
        
        // echo var_dump($noResult);
        if($noResult){
            echo '<div class="jumbotron jumbotron-fluid bg-secondary">
            <div class="container">
              <p class="display-4">No Comments Found</p>
              <p class="lead">Be the first person to comment.</p>
              <hr class="my-4">
            </div>
          </div>';
        }
    
    ?>

        </div>

        <?php include 'partials/_footer.php'; ?>

        <!-- Optional JavaScript; choose one of the two! -->
        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>