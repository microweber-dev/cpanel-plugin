<?php
$data = mw()->user_manager->session_get('mw_hosting_data');

$show_link = false;
if(isset($data['pid']) and ($data['pid'] == '1' or $data['pid'] == '19') ){
	$show_link = true;
}

if(isset($data['diskusage']) and (intval($data['diskusage']) > 0) and  isset($data['disklimit']) and (intval($data['disklimit']) > 0 ) ){
	
	if($data['disklimit'] - $data['diskusage'] < 200){
		$show_link = true;
		
	}
	
 
	
}


?>
<?php if ($data and isset($data['name'])): ?>
 
<div class="">
  <h2> Hosting info </h2>
  <div class="mw-ui-row">
    <div class="mw-ui-col"><?php print $data['name'] ?> (registered <?php print mw()->format->ago($data['regdate']) ?>) 
 

<?php if($show_link): ?>
      
      <a target="_blank" href="https://members.microweber.com/upgrade_product?product=<?php print $data['id'] ?> ">upgrade</a>
      
<?php endif; ?>      
      
      
      </div>
  </div>
</div>
<?php endif; ?>
