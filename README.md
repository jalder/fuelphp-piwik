fuelphp-piwik
=============

Piwik API module for FuelPHP

Must create a piwik.php file in your config directory containing the following PHP

return array(
	'api_key'=>'',
	'uri'=>'',
	'site_id'=>'',
	'colors'=>''
);

Examples:

`
$piwik = new Model_Piwik();
<img src="<?php echo $piwik->getImageSource('browsers','today','month'); ?>" alt="" title="" />


<table class="table">
	<thead>
		<tr><th>Keyword</th><th>Visits</th></tr>
	</thead>
	<tbody>
		<?php foreach($piwik->getKeywords() as $r): ?>
			<tr><td><?php echo $r->label; ?></td><td><?php echo $r->nb_visits; ?></td></tr>
		<?php endforeach; ?>
	</tbody>
</table>
`
