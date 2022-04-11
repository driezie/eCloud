<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>File download</title>
<style type="text/css">
    .img-box{
        display: inline-block;
        text-align: center;
        margin: 0 15px;
    }
</style>
</head>
<body>
    <?php
    // Array containing sample image file names
    $images = array("logo.jpg", "balloons.jpg");
    
    // Loop through array to create image gallery
    foreach($images as $image){
        echo '<div class="img-box">';
            echo '<img src="uploads/' . $image . '" style="width:200px">';
            echo '<p><a href="download.php?file=' . urlencode($image) . '">Download</a></p>';
        echo '</div>';
    }
    ?>
</body>
</html>