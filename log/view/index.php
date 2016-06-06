<!--

SCCM Log Viewer
@author Åsmund Stavdahl <asmund.stavdahl@itk.ntnu.no>

-->
<!DOCTYPE html>
<html>
<head>
	<title>SCCM Log Viewer</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"></link>

	<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.3.min.js"></script>

	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="/sorttable.js"></script>

	<link rel="stylesheet" type="text/css" href="/sccm/log/main.css"></link>
	<script type="text/javascript" src="/sccm/log/main.js"></script>
</head>
<body>

<?php

require_once("../../sccm.php");

# Hent innholder i loggfilen
$logContents = @file_get_contents($_FILES["log_contents"]["tmp_name"]);
if(!$logContents){
	exit("<h1>Empty or no file uploaded</h1><a href='../' class='btn btn-link'>Upload another file</a>");
}
# Windows liker å bruke \n\r istedet for \n
$logContents = str_replace("\n\r", "\n", $logContents);
# Del opp filen ved hvert linjeskift
$logLines = explode("\n", $logContents);

?>

<div class="container">
	<a name="top"></a>

	<div id="filter-tip" class="alert alert-info">
		<strong>Tip:</strong> type a regular expression in one or more of the filter
		inputs and press enter to show the matching log entries. Type just a "." (dot) to show everything.
	</div>
	<div id="no-match" class="alert alert-warning" style="display: none;">
		<strong>No matches.</strong> Try different search criteria.
	</div>

	<table class="table table-striped sortable">
		<tr>
			<!-- Header-celler for hver av kolonnene, med tekstbokser for mulighet til å filtrere radene -->
			<th>Time
				<div class="filter">
					<input type="text" class="form-control" data-prop="time" placeholder="regex" />
				</div>
			<th>Component
				<div class="filter">
					<input type="text" class="form-control" data-prop="component" placeholder="regex" />
				</div>
			<th>Context
				<div class="filter">
					<input type="text" class="form-control" data-prop="context" placeholder="regex" />
				</div>
			<th>Type
				<div class="filter">
					<input type="text" class="form-control" data-prop="type" placeholder="regex" />
				</div>
			<th>Title
				<div class="filter">
					<input type="text" class="form-control" data-prop="title" placeholder="regex" />
				</div>
			<th>Thread
				<div class="filter">
					<input type="text" class="form-control" data-prop="thread" placeholder="regex" />
				</div>
			<th>File
				<div class="filter">
					<input type="text" class="form-control" data-prop="file" placeholder="regex" />
				</div>

	<?php
	# Lag en rad i tabellen for hver linje i loggfilen
	foreach($logLines as $l){
		# Lag et SCCM_Log_Entry-object for linja
		$e = new SCCM_Log_Entry($l);
		# Hvis logglinja mangler time eller title tar vi og går til neste
		if(!@$e->time || !@$e->title) continue;
	?>
		<tr class="log-entry">
			<td data-prop="time"><span><?php echo $e->time; ?></span>
			<td data-prop="component"><span><?php echo $e->component; ?></span>
			<td data-prop="context"><span><?php echo $e->context; ?></span>
			<td data-prop="type"><span><?php echo $e->type; ?></span>
			<td data-prop="title"><code><?php echo $e->title; ?></code>
			<td data-prop="thread"><span><?php echo $e->thread; ?></span>
			<td data-prop="file"><span><?php echo $e->file; ?></span>
	<?php } ?>
	</table>

	<!-- noen knapper og sånnt nederst i hjørnet for å 1) gå tilbake til opplastningssiden, 2) på til toppen av loggen og 3) vise hvor mange rader som matcher filtrene -->
	<div id="hud-wrap" class="well">
		<a href="../" class="col-sm-6 btn btn-default" role="button">
			<div id="backlink">
				<b>Close</b>
			</div>
		</a>
		<a href="#top" class="col-sm-3 btn btn-default" role="button">
			<div id="uplink">
				<b>↑</b>
			</div>
		</a>
		<div id="match-counter" class="col-sm-3 btn disabled">
			0
		</div>
	</div>
</div>
</body>
</html>
