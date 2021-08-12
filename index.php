<?php  
include "setting.php";
$status=0;
$msg="";
if(isset($_POST['submit'])){
	$name = $_POST['name'];
	$path = addslashes($_POST['path']);
	if(empty($name) || empty($path)){
		echo "<script>alert('Data kosong harap periksa kembali');</script>";
		
	}else{
		$sql = "SELECT count(*) FROM `vm` WHERE `path`='$path'";
		$res = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($res)) {
			if($row[0]==1){
				echo "Failed, Path already exist";
				exit();
			}
		}

		$sql = "INSERT INTO `vm`(`name`, `path`, `snap`) VALUES ('$name','$path',0)";
		mysqli_query($con, $sql);
		$status=1;
		$msg="Berhasil menambahkan ".$name;
	}
}

if(isset($_POST['edit'])){
	$id = $_GET['edit'];
	$name = $_POST['name'];
	$path = addslashes($_POST['path']);
	if(empty($name) || empty($path)){
		echo "<script>alert('Data kosong harap periksa kembali');</script>";
		
	}else{

		$sql = "SELECT count(*) FROM `vm` WHERE `path`='$path' AND `id`!=$id";
		$result = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($result)) {
			if($row[0]==1){
				echo "Failed, Path already exist";
				exit();
			}
		}

		$sql = "UPDATE `vm` SET `name`='$name',`path`='$path' WHERE `id`=$id";
		$res = mysqli_query($con, $sql);
		if($res){
			header("Location: index.php");
		}
	}
}

if(isset($_GET['delete']))
{
	$delete_id=$_GET['delete'];
		echo "<script>alert('Data kosong harap periksa kembali');</script>";
	

	$sql="DELETE FROM vm WHERE id='$delete_id'";
	$res = mysqli_query($con, $sql);

	if($res)
	{
		header("Location: index.php");
	}
}

$sql = "SELECT * FROM vm";
$result = mysqli_query($con, $sql);
$lists = array();
while($row = mysqli_fetch_array($result)){
	$lists[] = $row;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Tekvir</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans&display=swap" rel="stylesheet">

	<style type="text/css">
		*{
			font-family: 'Balsamiq Sans', cursive;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row mt-3">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<?php if ($status):1 ?>
				 	<div class="alert alert-success" role="alert">
				 		<?php echo $msg; ?>
				 	</div>
				<?php endif ?>
				<h3>VM</h3>
				<hr style="border: 1px solid black;">
				<div class="row my-3">
					<div class="col-md-4">
						<button class="btn btn-primary" data-toggle="modal" data-target="#addModal"><img src="img/add.svg" class="mx-2" height="20px" widht="20px"><span style="font-size: 1em; font-weight: 16px;">Add VM</span></button>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-hover mt-3">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Path</th>
								<!-- <th scope="col" class="text-center">Power</th> -->
								<th scope="col" class="text-center">Action</th>
							</tr>
						</thead>
						<tbody class="">
							<?php foreach ($lists as $list): ?>
								<tr>
									<td><?php echo $list['name'] ?></td>
									<td><?php echo $list['path'] ?></td>

									<td>
										<!-- <a href="adminindex.php?deleteteam=<?php  echo $team['id']; ?>" class="btn btn-danger ">Delete</a> -->
										<a href="detail.php?detail=<?php echo $list['id'] ?>" class="btn btn-info btn-block">Detail</a>
										<!-- <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#editModal">Detail</button> -->
										<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#editModal">Edit</button>
										<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">Delete</button>
										
									</td>

									<!-- editModal -->
									<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editTitle" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered" role="document">
											<div class="modal-content">

												<div class="modal-header">
													<h3 class="modal-title">Add VM</h3>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>

												<div class="modal-body">
													<form action="index.php?edit=<?php echo $list['id'] ?>" method="POST">
														<div class="form-group">
															<label for="vmName">VM Name</label>
															<input type="text" class="form-control" id="name" name="name" placeholder="VM Name" value="<?php echo $list['name']; ?>">
														</div>
														<div class="form-group">
															<label for="vmPath">VM Path</label>
															<input type="text" class="form-control" id="path" name="path" placeholder="VM Path" value="<?php echo $list['path']; ?>">
														</div>
														<button type="edit" name="edit" class="btn btn-danger btn-block">Edit</a>
													</form>
														
												</div>

											</div>
										</div>
									</div>

									<!-- DeleteModal -->
									<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h3 class="modal-title">Delete</h3>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>

												<div class="modal-body">
													<form action="" method="POST">
														<h5>Apakah kamu yakin untuk menghapus vm ini?</h5>
														<a href="index.php?delete=<?php echo $list['id'] ?>" class="btn btn-danger btn-block">Delete</a>
													</form>
														
												</div>
											</div>
										</div>
									</div>

								</tr>

							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-2"></div>

		</div>

		<!-- VM Modal -->
		<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="vmTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<h3 class="modal-title">Add VM</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<form action="" method="POST">
							<div class="form-group">
								<label for="vmName">VM Name</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="VM Name">
							</div>
							<div class="form-group">
								<label for="vmPath">VM Path</label>
								<input type="text" class="form-control" id="path" name="path" placeholder="VM Path">
							</div>
							<button type="submit" class="btn btn-primary btn-block" id="vmSubmit" name="submit">Add VM</button>
						</form>
							
					</div>

				</div>
			</div>
		</div>
	</div>
		

	


	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>