<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Manager</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body style="height: 100vh">

    <div class="container">
    <div class="d-flex justify-center align-center flex-column">
    <?php
    session_start();
    if (!isset($_SESSION['file'])) { ?>
        <h3>Upload file </h3>
        <form action="./functions.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="">
            <button type="submit">Upload</button>
        </form>

    <?php } ?>

    <?php

    if (isset($_SESSION['file'])) {
        ?>
        <H3>Archive files</H3>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Size</th>
                    <th scope="col">Encryption</th>
                    <th scope="col">Actions</th>

                </tr>
            </thead>
   
            <?php
            $files = $_SESSION['file']['data'];
            $fileID = $_SESSION['id'];

            foreach ($files as $file):
                ?>
                <tr>
                    <td scope="row">
                        <?php echo $file['index'] ?>
                    </td>
                    <td>
                        <?php echo $file['name'] ?>
                    </td>
                    <td>
                        <?php echo $file['size'] ?>
                    </td>
                    <td>
                        <?php echo $file['encryption_method'] == 0 ? 'None' : $file['encryption_method'] ?>
                    </td>
                    <td>
                        <div class="row gx-3">
                            <div class="col-4">
                            <form id="<?php echo $file['index'] ?>" action="functions.php?action=delete" method="post">
                            <input type="hidden" name="file-index" value="<?php echo $file['index'] ?>">
                            <input type="hidden" name="file-name" value="<?php echo $file['name'] ?>">
                            <input type="hidden" name="fileID" value="<?php echo $fileID ?>">
                            <button type="submit" class="btn btn-danger">Delete file</button>
                        </form>
                            </div>

                            <div class="col-1">
                                    <form id="<?php echo $file['index'] ?>" action="functions.php?action=rename" method="post">
                            <input type="hidden" name="file-name" value="<?php echo $file['name'] ?>">
                            <input type="hidden" name="fileID" value="<?php echo $fileID ?>">
                            <button type="submit" class="btn btn-warning">Rename</button>
                        </form>
                            </div>
                        </div>
                        
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php } ?>

    <?php if (isset($_SESSION['file'])) { ?>
        <div class="row ">
            <h3>Add file to archive </h3>
            <p>Upload a file to be added to the current archive</p>
            <form action="./functions.php?action=append" method="post" enctype="multipart/form-data">
                <div class="row align-items-start">
                    <div class="col-6">
                        <div class="mb-3">
                            <input class="form-control" type="file" id="formFile"name="file">
                        </div>
                        <input type="hidden" name="id" id="" value="<?php echo $fileID ?>">
                    </div>
                    <div class="col-1">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                    <div class="col-1">
                        <button type="submit" class="btn btn-danger">Clear</button>
                    </div>
                </div>
                
                
            </form>
        </div>
    <?php } ?>
    </div>
    </div>
</body>

<script src="assets/js/bootstrap.bundle.min.js"></script>

</html>