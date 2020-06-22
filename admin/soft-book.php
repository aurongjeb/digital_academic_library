<?php
//including the database connection file
include_once("config2.php");

//fetching data in descending order (lastest entry first)
//$result = mysql_query("SELECT * FROM users ORDER BY id DESC"); // mysql_query is deprecated
$result = mysqli_query($mysqli, "SELECT * FROM files ORDER BY id DESC"); // using mysqli_query instead
?>
<?php
session_start();
error_reporting(0);
include('includes/config.php');
require 'filesLogic.php' ;
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
if (isset($_POST['save'])) { // if save button on the form is clicked
    // name of the uploaded file
    $filename = $_FILES['myfile']['name'];

    // destination of the file on the server
    $destination = 'uploads/' . $filename;

    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['myfile']['tmp_name'];
    $size = $_FILES['myfile']['size'];

    if (!in_array($extension, ['zip', 'pdf', 'docx'])) {
        echo "You file extension must be .zip, .pdf or .docx";
    } elseif ($_FILES['myfile']['size'] > 10000000) { // file shouldn't be larger than 1Megabyte
        echo "File too large!";
    } else {
        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($file, $destination)) {
            $sql = "INSERT INTO files (name, size, downloads) VALUES ('$filename', $size, 0)";
         if (mysqli_query($conn, $sql)) {
                echo "File uploaded successfully";
            }
			 
        } else {
            echo "Failed to upload file.";
           
		
    }
	
}
header('location:soft-book.php');
}
if(isset($_GET['del']))
{
	$id=intval($_GET['del']);
	$adn="delete from files where id=?";
	$stmt= $mysqli->prepare($adn);
	$stmt->bind_param(i,$id);
	$rs=$stmt->execute();
	if(rs==true)
	{
	 
	 header('location:soft-book.php');
	}
	
	

}
    ?>
<!DOCTYPE html>
<html  >
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title> Digital Academic Library</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Soft Books</h4>
    </div>
     <div class="row">
    <?php if($_SESSION['error']!="")
    {?>
<div class="col-md-6">
<div class="alert alert-danger" >
 <strong>Error :</strong> 
 <?php echo htmlentities($_SESSION['error']);?>
<?php echo htmlentities($_SESSION['error']="");?>
</div>
</div>
<?php } ?>
<?php if($_SESSION['msg']!="")
{?>
<div class="col-md-6">
<div class="alert alert-success" >
 <strong>Success :</strong> 
 <?php echo htmlentities($_SESSION['msg']);?>
<?php echo htmlentities($_SESSION['msg']="");?>
</div>
</div>
<?php } ?>


 

</div>
	

        </div>
            <div class="row">
                <div class="col-md-12">
				<div class="panel-heading">
					<form action="" method="post" enctype="multipart/form-data" >
					<h3>Upload File</h3>
					<input type="file" name="myfile"> <br>
					<button type="submit" name="save">upload</button>
				</div>	 
					</form>
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                          Books List 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>File Name</th>
                                            <th>Size</th>
                                            <th>Download</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
   
<?php foreach ($files as $file): ?>                                  
                                        <tr class="odd gradeX">
                                            <td class="center"><?php echo $file['id']; ?></td>
                                            <td class="center"><?php echo $file['name']; ?></td>
                                            <td class="center"><?php echo floor($file['size'] / 1000) . ' KB'; ?></td>
                                            <td class="center"><?php echo $file['downloads']; ?></td>
                                            <td class="center"><a href="server.php?file_id=<?php echo $file['id'] ?>"><button class="btn btn-primary">Download</button>
                                            <?php echo "<a href=\"delete.php?id=$file[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">";?><button class="btn btn-danger">Delete</button></a>

                                            
											 
                                         
                                            </td>
                                        </tr>
<?php endforeach;?>                                    
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>


            
    </div>
    </div>

     <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
