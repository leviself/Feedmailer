<?php
	$mysql = new db();
	$mysql->connect();
	include dirname(__FILE__)."/functions.php";

	$debug = new debugger();

	$term = $_GET[term];
	$results = isset($_GET[results]) ? $_GET[results] : $mysql->setting(debug_limit);
	$orderby = isset($_GET[orderby]) ? $_GET[orderby] : $mysql->setting(debug_orderby);
	$page = isset($_GET[page]) ? $_GET[page] : 0;

	define("COUNT_DEBUG", mysql_result(mysql_query(
	"SELECT COUNT(*) FROM `{$mysql->prefix}debug` 
	WHERE type LIKE '%$term%' OR message LIKE '%$term%'"),0));


?>
<?php admin_pagenav("Debug"); ?>
	<form action="<?php echo SITE_URL;?>debug.php" method="get">
	<div id="page">
		<div id="boxpadding">
			<table>
				<tbody>
					<tr>
						<td>
							Search:<br />
						</td>
					</tr>
						<td>
							<input type="text" name="term" value="<?php echo $term;?>" class="inputbox"/>
							<select name="orderby" class="inputbox">
								<option value="ASC" <?php if($orderby == "ASC"){ echo 'selected="selected" ';}?>>ASC</option>
								<option value="DESC" <?php if($orderby == "DESC"){ echo 'selected="selected" ';}?>>DESC</option>
							</select>
							<select name="results" class="inputbox">
								<option value="25" <?php if($results == "25"){ echo 'selected="selected" ';}?>>25</option>
								<option value="50" <?php if($results == "50"){ echo 'selected="selected" ';}?>>50</option>
								<option value="75" <?php if($results == "75"){ echo 'selected="selected" ';}?>>75</option>
								<option value="100" <?php if($results == "100"){ echo 'selected="selected" ';}?>>100</option>
							</select>
						</td>
						<td>
							<input type="submit" class="submit" value="Search" />
						</td>
					</tr>
				</tbody>
			</table>


<?php
if ($page == 0){
	$start = 0;
	$finish = $results;

	$bn_next = $page + $results;
	$page_output_next = ($results >= COUNT_DEBUG) ? "" : '<a class="links" href="?term='.$term.'&page='.$bn_next.'&results='.$results.'&orderby='.$orderby.'">Next &#187</a>';
?>
			<table id="debug">
				<tbody>					
					<?php $debug->showDebug($term, $orderby, $start, $finish);?>
				</tbody>
			</table>
			<p>
				<table style="margin-left:58%;">
					<tbody>
						<tr>
							<td><?php echo $page_output_prev;?></td>
							<td style="width:86%;"></td>
							<td><?php echo $page_output_next;?></td>
						</tr>
					</tbody>
				</table>
			</p>
<?php
} else {
	$bn_next = $page + $results;
	$bn_prev = $page - $results;

	$bn_prev = ($bn_prev < 0) ? 0 : $bn_prev;

	$page_output_next = ($bn_next >= COUNT_DEBUG) ? "" : '<a href="?term='.$term.'&page='.$bn_next.'&results='.$results.'&orderby='.$orderby.'">Next &#187</a>';
	$page_output_prev = ($bn_prev < 0) ? "" : '<a href="?term='.$term.'&page='.$bn_prev.'&results='.$results.'&orderby='.$orderby.'">&#171 Previous</a>';
?>
	
			<table id="debug">
				<tbody>					
					<?php $debug->showDebug($term, $orderby, $page, $results);?>
				</tbody>
			</table>

			<p>
				<table>
					<tbody>
						<tr>
							<td><?php echo $page_output_prev;?></td>
							<td style="width:86%;"></td>
							<td><?php echo $page_output_next;?></td>
						</tr>
					</tbody>
				</table>
			</p>
<?php
} ?>
		</div>
	</div>
	</form>
