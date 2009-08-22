<?php	// Load the templates class
	$template = new templates();
	include dirname(__FILE__)."/functions.php";

	// Load the MySQL Connection
	$mysql = new db();
	$mysql->connect();

	$results = isset($_GET[results]) ? $_GET[results] : 10;
	$orderby = isset($_GET[orderby]) ? $_GET[orderby] : "DESC";

	$page = isset($_GET[page]) ? $_GET[page] : 0;
?>

				<?php admin_pagenav("Users"); ?>
				<?php	// Load the sidebar
					$template->sidebar();
				?>
				<div id="sidebar_page">

			                <script type="text/javascript">
		        	                function checkUncheckAll(theElement) {
						var theForm = theElement.form, z = 0;
						for(z=0; z<theForm.length;z++){
							if(theForm[z].type == "checkbox" && theForm[z].name != "checkall"){
								theForm[z].checked = theElement.checked;
							}
						}
					}
					</script>

					<div id="boxpadding">
						<p class="smalltext">
							You can edit a user by clicking on the user's
							username. <br />To delete a user, tick the box for the
							specified user and click "Delete".
						</p>
						<form action="<?php echo $_SERVER[PHP_SELF];?>" method="get">
						Show:
						<select name="results">
							<option value="10" <?php if($results == "10"){ echo 'selected="selected" ';}?>>10</option>
							<option value="25" <?php if($results == "25"){ echo 'selected="selected" ';}?>>25</option>
							<option value="50" <?php if($results == "50"){ echo 'selected="selected" ';}?>>50</option>
						</select>

						<select name="orderby">
							<option value="DESC" <?php if($orderby == "DESC"){ echo 'selected="selected" ';}?>>Descending</option>
							<option value="ASC" <?php if($orderby == "ASC"){ echo 'selected="selected" ';}?>>Ascending</option>
						</select>

						<input type="submit" value="Change"/>
						</form>
						<br />
<?php
	if($page == 0){
		$start = 0;
		$finish = $results;

		$bn_next = $page + $results;
		$page_output_next = ($results >= COUNT_REG) ? "" : '<a class="links" href="?page='.$bn_next.'&results='.$results.'&orderby='.$orderby.'">Next &#187</a>';
?>
		
						<form action="<?php echo $_SERVER[PHP_SELF];?>" method="post">
						<table cellpadding="2">
							<tbody>
								<tr>
									<td><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);" /></td>
									<td><strong>ID</strong></td>
									<td><strong>Username</strong></td>
									<td><strong>Email</strong></td>
								</tr>
								<?php	// Show All Users
									$mysql->showAllUsers($orderby,$start,$finish);
								?>

							</tbody>
						</table>
						<p>
							<table id="pagination" style="margin-left:58%;">
								<tbody>
									<tr>
										<td><?php echo $page_output_prev;?></td>
										<td style="width:69%;"></td>
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

		$page_output_next = ($bn_next >= COUNT_REG) ? "" : '<a href="?page='.$bn_next.'&results='.$results.'&orderby='.$orderby.'">Next &#187</a>';
		$page_output_prev = ($bn_prev < 0) ? "" : '<a href="?page='.$bn_prev.'&results='.$results.'&orderby='.$orderby.'">&#171 Previous</a>';

?>
						<form action="<?php echo $_SERVER[PHP_SELF];?>" method="post">
						<table cellpadding="2">
							<tbody>
								<tr>
									<td><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);" /></td>
									<td><strong>ID</strong></td>
									<td><strong>Username</strong></td>
									<td><strong>Email</strong></td>
								</tr>
								<?php	// Show All Users
									$mysql->showAllUsers($orderby,$page,$results);
								?>

							</tbody>
						</table>
						<p>
							<table id="pagination" >
								<tbody>
									<tr>
										<td><?php echo $page_output_prev;?></td>
										<td style="width:69%;"></td>
										<td><?php echo $page_output_next;?></td>
									</tr>
								</tbody>
							</table>
						</p>
<?php
	}
?>
						<p>
							<input type="submit" class="submit" value="Delete" />
						</p>
					</div>
				</div>
				</form>
				<br /><br />
