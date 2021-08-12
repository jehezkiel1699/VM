<?php 
include "setting.php";

$exe = "powerOn.exe";

$id = $_GET['detail'];

$output = "";
$status = 0;

$sql = "SELECT * FROM vm WHERE id = $id";
$result = mysqli_query($con, $sql);
// $name = array();
// $path = array();
while($row = mysqli_fetch_array($result)){
	$name[] = $row['name'];
	$path[] = $row['path'];
}

if(isset($_POST['on'])){

	$output = cmd("$exe start $path[0]"); 
	$status = 1;
}
if(isset($_POST['pause'])){

	$output = cmd("$exe suspend $path[0]"); 
	$status = 1;

}

if(isset($_POST['off'])){

	$output = cmd("$exe stop $path[0]"); 
	$status = 1;

}

if(isset($_POST['create'])){
	$snapshotname=$_POST['cname'];

	if(empty($snapshotname)){
		echo "<script>alert('Data Invalid');</script>";
	}else{
		// shell_exec("PATH=%PATH%;C:\\Program Files (x86)\\VMware\\VMware VIX\\Workstation-15.0.0\\32bit&& vix.exe csshot D:\VM\CentOS\CentOS.vmx $snapshotname");
		$output = cmd("$exe csshot $path[0] $snapshotname"); 
		$status = 1;
	}

}

if(isset($_POST['revert'])){
	$snapshotname=$_POST['cname'];

	if(empty($snapshotname)){
		echo "<script>alert('Data Invalid');</script>";
	}else{
		// shell_exec("PATH=%PATH%;C:\\Program Files (x86)\\VMware\\VMware VIX\\Workstation-15.0.0\\32bit&& vix.exe rsshot D:\VM\CentOS\CentOS.vmx $snapshotname");
		$output = cmd("$exe rsshot $path[0] $snapshotname"); 
		$status = 1;
	}
	
}

if(isset($_POST['runcmd'])){
	$exe1 = "LabEval.exe";

	if(empty($_POST['command'])){
		echo "<script>alert('Command tidak boleh kosong');</script>";

	}else{
		$cfgfile = "guest.cfg";
		$code = $_POST['command'];
		$output = cmd("$exe1 $cfgfile $code");
		
		// $output = cmd("run.exe \"$path[0]\" \"$code\"");
		$status = 1;

	}

	

}

if(isset($_POST['clone'])){
	if(empty($_POST['pathclone'])){
		echo "<script>alert('Path tidak boleh kosong');</script>";

	}else{
		$clonePath = $_POST['pathclone'];
		$output = cmd("$exe clone $path[0] $clonePath"); 
		$status = 1;
	}
}

if(isset($_POST['runcode'])){
	
	$path = addslashes($path[0]);
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$runcode = $_POST['runcode'];
	$target_file = basename("shell");
	move_uploaded_file($_FILES["code"]["tmp_name"], "$target_file");
	$output = cmd("Project1.exe \"$path\" \"$user\" \"$pass\" \"$runcode\" \"shell\"");
	$status = 1;
	
}


 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Detail VM</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<link href="https://fonts.googleapis.com/css2?family=Balsamiq+Sans&display=swap" rel="stylesheet">

	<style type="text/css">
		*{
			font-family: 'Balsamiq Sans', cursive;
		}
		.image-center{
			display: block;
			  margin-left: auto;
			  margin-right: auto;
			  width: 50%;
		}
	</style>
	
</head>
<body>
	<div class="container-fluid">
		<div class="row mt-3">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-12">
						<a href="index.php" class="btn btn-success mb-3"><img src="img/backward.svg" class="mx-2" height="20px" widht="20px"><span style="font-size: 1em; font-weight: 16px;">Back</span></a>
						<h3>Konfigurasi</h3>
						<hr style="border: 1px solid black;">

						<h5>Name: <?php echo $name[0]; ?></h5>
						<h5>Path: <?php echo $path[0]; ?></h5>
						
						<button class="btn btn-outline-primary my-3 mr-3" data-toggle="modal" data-target="#confModal"><img src="img/stand-by.svg" class="mx-2" height="20px" widht="20px"><span style="font-size: 1em; font-weight: 16px;">Power Option</span></button>
						<button class="btn btn-outline-success my-3 mr-3" data-toggle="modal" data-target="#snapModal"><img src="img/add.svg" class="mx-2" height="20px" widht="20px"><span style="font-size: 1em; font-weight: 16px;">Snapshot</span></button>
						<button class="btn btn-outline-info my-3 mr-3" data-toggle="modal" data-target="#cloneModal"><img src="img/server.svg" class="mx-2" height="20px" widht="20px"><span style="font-size: 1em; font-weight: 16px;">Clone</span></button>

						<button class="btn btn-outline-secondary my-3 mr-3" data-toggle="modal" data-target="#guestModal"><img src="img/terminal.svg" class="mx-2" height="20px" widht="20px"><span style="font-size: 1em; font-weight: 16px;">GuestOps</span></button>

						<button class="btn btn-outline-warning my-3 mr-3" data-toggle="modal" data-target="#scriptModal"><img src="img/terminal.svg" class="mx-2" height="20px" widht="20px"><span style="font-size: 1em; font-weight: 16px;">Run Script</span></button>

					</div>
				</div>

				<div class="row mt-3">
					<div class="col-md-12">
						
						<h3>Output: </h3>
						<hr style="border: 1px solid black;">
						<?php if ($status):1 ?>
							<span class="mt-3" style="font-size: 1em; font-weight: 16px;"><?php echo $output ?></span>
							<?php 
								$output="";
								$status=0;
							?>
						<?php endif ?>
					</div>
				</div>
				

				
			

				<!-- <div class="row mt-3">
					<div class="col-md-12">
						<form action="" method="POST">
							<div class="form-group isi">
								<label for="command">Command :</label>
								<input type="text" class="form-control" id="command" name="command" placeholder="Command">
							</div>
							<button type="create" class="btn btn-primary text-right" name="check"><span style="font-size: 1em; font-weight: 16px;">Check</span></button>
						</form>
					</div>
				</div> -->
				
			</div>
			<div class="col-md-2"></div>

		</div>


		<!-- ConfModal -->
		<div class="modal fade" id="confModal" tabindex="-1" role="dialog" aria-labelledby="confTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Power Option</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<form action="" method="POST">
							<div class="row">
								<div class="col-md-4 mt-3">
									<button type="off" class="btn btn-danger btn-block h-100" name="off"><img src="img/stop.svg" class="mx-2" height="20px" widht="20px"><br><span style="font-size: 1em; font-weight: 16px;">STOP</span></button>
								</div>
								<div class="col-md-4 mt-3">
									<button type="pause" class="btn btn-secondary btn-block h-100" name="pause"><img src="img/pause.svg" class="mx-2" height="20px" widht="20px"><br><span style="font-size: 1em; font-weight: 16px;">PAUSE</span></button>
								</div>
								<div class="col-md-4 mt-3">
									<button type="on" class="btn btn-primary btn-block h-100" name="on"><img src="img/play-button.svg" class="mx-2" height="20px" widht="20px"><br><span style="font-size: 1em; font-weight: 16px;">START</span></button>
								</div>
							</div>

							
						</form>
						
					</div>
				</div>
			</div>
		</div>

		<!-- Snapshot Modal -->

		<div class="modal fade" id="snapModal" tabindex="-1" role="dialog" aria-labelledby="confTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Snapshot Option</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<form action="" method="POST">
							<div class="form-group isi">
								<label for="cname">Snapshot Name :</label>
								<input type="text" class="form-control" id="cname" name="cname" placeholder="Snapshot Name">
							</div>
							<button type="create" class="btn btn-primary btn-block h-100" name="create"><img src="img/add.svg" class="mx-2" height="20px" widht="20px"><br><span style="font-size: 1em; font-weight: 16px;">Create Snapshot</span></button>
							<button type="revert" class="btn btn-primary btn-block h-100" name="revert"><img src="img/backward.svg" class="mx-2" height="20px" widht="20px"><br><span style="font-size: 1em; font-weight: 16px;">Revert Snapshot</span></button>

						</form>
						
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- GuestOps Modal -->
	<div class="modal fade" id="guestModal" tabindex="-1" role="dialog" aria-labelledby="guestTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Guest Option</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="" method="POST">
					<!-- <div class="form-group isi">
							<label for="command">Command :</label>
							<input type="text" class="form-control" id="command" name="command" placeholder="Guest Username">
					</div> -->
						<div class="form-group isi">
							<label for="command">Command :</label>
							<input type="text" class="form-control" id="command" name="command" placeholder="Command">
						</div>
						<button type="create" class="btn btn-secondary btn-block h-100" name="runcmd"><span style="font-size: 1em; font-weight: 16px;">Run Command</span></button>
						
					</form>
					
				</div>
			</div>
		</div>
	</div>

	<!-- Script Modal -->
	<div class="modal fade" id="scriptModal" tabindex="-1" role="dialog" aria-labelledby="scriptTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Script Option</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="" method="POST"  enctype="multipart/form-data">
						<div class="form-group isi">
						<div class="row mb-3">
							<div class="col-12">
								<input type="text" class="form-control h-100" id="user" name="user" placeholder="Enter Guest Username" required>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-12">
								<input type="password" class="form-control h-100" id="pass" name="pass" placeholder="Enter Guest Password" required>
							</div>
						</div>
						<hr>
						<div class="row mb-3">
							<div class="col-8">
								<input type="hidden" class="form-control h-100" id="code" name="shell" placeholder="Enter Terminal Command">
							</div>
							<!-- <div class="col-4">
								<button type="hidden" class="btn btn-warning btn-block" id="shell" name="runcode" value="shell">Run Shell</button>
							</div> -->
						</div>

							<label for="code">Code :</label>
							<input type="file" class="form-control" id="code" name="code" placeholder="Code">
						</div>
						<button type="create" class="btn btn-warning btn-block h-100" name="runcode" value="python">
							<span style="font-size: 1em; font-weight: 16px;">Run</span>
						</button>
						<!-- <button type="create" class="btn btn-warning btn-block h-100" name="runcode" value="bash">
							<span style="font-size: 1em; font-weight: 16px;">Bash</span>
						</button>
						<button type="create" class="btn btn-warning btn-block h-100" name="runcode" value="php">
							<span style="font-size: 1em; font-weight: 16px;">Php</span>
						</button>
						<button type="create" class="btn btn-warning btn-block h-100" name="runcode" value="perl">
							<span style="font-size: 1em; font-weight: 16px;">Perl</span>
						</button> -->
						
					</form>
					
				</div>
			</div>
		</div>
	</div>

	<!-- Clone Modal -->
	<div class="modal fade" id="cloneModal" tabindex="-1" role="dialog" aria-labelledby="cloneTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Clone</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="" method="POST">
						<div class="form-group isi">
							<label for="pathclone">Path :</label>
							<input type="text" class="form-control" id="pathclone" name="pathclone" placeholder="Path Clone">
						</div>
						<button type="create" class="btn btn-info btn-block h-100" name="clone"><span style="font-size: 1em; font-weight: 16px;">Clone</span></button>
						
					</form>
					
				</div>
			</div>
		</div>
	</div>
		


	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<script type="text/javascript">
		

	</script>

</body>
</html>