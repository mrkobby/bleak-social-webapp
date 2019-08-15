<?php
$enemyList = '';
$total_enemies = '';
$hide_search = 'style="display:;"';
$person = 'Your';
$sql = "SELECT COUNT(id) FROM enemies WHERE user1='$u'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$enemy_count = $query_count[0];
if($enemy_count < 1 && $u == $log_username){
	$enemyList = '<span style="color: rgb(45, 137, 180);margin-left:38%;"> You have no haters yet </span>';
}else if($u != $log_username){
	$person = ''.$u.'\'s';
	$total_enemies = '';
	$enemyList = '<span style="color: rgb(45, 137, 180);margin-left:38%;">'.$u.' has no haters yet </span>';
	$hide_search = 'style="display:none;"';
}else if($enemy_count < 1 && $u != $log_username){
	$hide_search = 'style="display:none;"';
}
?>

<div class="cd fade" id="enemyModal" tabindex="-1" role="dialog" aria-labelledby="enemyModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="d">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class=""><?php echo $person ?> Enemies</h4>
      </div>

      <div class="modal-body amf">
		<div class="input-group" <?php echo $hide_search ?>>
			<input type="text" class="form-control" placeholder="Search">
			<div class="fj"">
				<button type="button" class="cg fm">
					<span class="fa fa-search" ></span>
				</button>
			</div>
		</div>
        <div class="uq">
          <div class="qo cj ca">
			<?php echo $enemyList ?>
          </div>
        </div>
      </div>
	  <hr>
	  <div class="modal-footer" style="text-align: center;padding: 10px;">
		 <span class="text-center" href="#">
            <?php echo $total_enemies ?>
	     </span>
	  </div>
    </div>
  </div>
</div>