<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Manager</title>
</head>
<body>
    
    <?php
        session_start();
        if(!isset($_SESSION['file'])){  ?>
        <h3>Upload file </h3>
        <form action="./functions.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="">
            <button type="submit">Upload</button>
        </form>

   <?php } ?>

    <?php

    if(isset($_SESSION['file'])) { 
    ?>
    <H3>Archive files</H3>
    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Size</th>
            <th>Encryption</th>
            <th>Actions</th>

        </tr>
            <?php 
                $files = $_SESSION['file']['data'];
                $fileID = $_SESSION['id'];
                var_dump($_SESSION['file']['data']);
                foreach($files as $file):
            ?>
                <tr>
                    <td><?php echo $file['index'] ?></td>
                    <td><?php echo $file['name'] ?></td>
                    <td><?php echo $file['size'] ?></td>
                    <td>
                        <?php echo $file['encryption_method'] == 0 ? 'None' : $file['encryption_method']?>
                    </td>
                    <td>
                        <form id="<?php echo $file['index'] ?>" action="functions.php?action=delete" method="post">
                            <input type="hidden" name="file-index" value="<?php echo $file['index'] ?>">
                            <input type="hidden" name="file-name" value="<?php echo $file['name'] ?>">
                            <input type="hidden" name="fileID" value="<?php echo $fileID ?>">
                            <button type="submit">Delete file</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
    </table>
    <?php } ?>

    
    <?php if(isset($_SESSION['file'])){  ?>
        <h3>Add file to archive </h3>
        <p>Upload a file to be added to the current archive</p>
        <form action="./functions.php?action=append" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="">
            <input type="hidden" name="id" id="" value="<?php echo $fileID ?>">
            <button type="submit">Upload</button>
        </form>

    <?php } ?>
</body>
</html>