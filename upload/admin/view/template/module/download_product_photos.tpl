<?php echo $header; ?>
<style>
.hidden{
	display:none;
}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a class="button" id="save-module-settings"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div><!--end heading-->
    <div class="content">
		<table class="form list">
			<thead>
				<tr>
					<td>Module Setting</td>
					<td>About This Module</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select id="module-setting">
							<option value="false">Disabled</option>
							<option value="true">Enabled</option>
						</select>
					</td>
					<td>
						<p>Write a short description of the module</p>
					</td>
				</tr>
			</tbody>
		</table><!--end table-->
    </div><!--end content-->
  </div>
</div>
<script src="view/javascript/downloadProductPhotos.js"></script>
<?php echo $footer; ?>