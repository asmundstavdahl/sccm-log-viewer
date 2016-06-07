<!--

SCCM Log Viewer
@author Åsmund Stavdahl <asmund.stavdahl@itk.ntnu.no>

-->
<!DOCTYPE html>
<html>
<head>
	<title>SCCM Log Utilities</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"></link>

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.3.min.js"></script>

	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
	<form action="view/" method="POST" enctype="multipart/form-data">
		<div class="form-group">
			<label for="log_contents">Select log to upload</label>
			<input type="file" name="log_contents" id="log_contents">
		</div>
		<input type="submit" value="View" name="submit" class="btn btn-default">
	</form>
</div>
</body>
</html>
