<form name="<?php echo $this->getPlugin()->getSlug(); ?>_domain_mapping_form" id="<?php echo $this->getPlugin()->getSlug(); ?>_domain_mapping_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<?php settings_fields($this->getPlugin()->getSlug()); ?>

<p>Domain mapping allows you to map external domains that host their HTTPS content on a different domain. You may use regular expressions.</p>

<table class="form-table" id="domain_mapping">
	<thead>
	</thead>
<?php
	$ssl_host_mapping = ( is_array($this->getPlugin()->getSetting('ssl_host_mapping')) ? $this->getPlugin()->getSetting('ssl_host_mapping') : array() );
	foreach( $ssl_host_mapping as $http_domain => $https_domain ) {
?>
	<tr valign="top" class="domain_mapping_row">
		<td class="http_scheme">
			<span class="label">http://</span>
		</td>
		<td class="http_domain">
			<input type="text" name="http_domain[]" value="<?=$http_domain?>" />
		</td>
		<td class="arrow">
			<span class="label">&gt;</span>
		</td>
		<td class="https_scheme">
			<span class="label">https://</span>
		</td>
		<td class="https_domain">
			<input type="text" name="https_domain[]" value="<?=$https_domain?>" />
		</td>
		<td class="controls">
			<a class="remove" href="#" title="Remove URL Filter">Remove</a>
			<a class="add" href="#" title="Add URL Filter">Add</a>
		</td>
	</tr>

<?php } ?>
	<tr valign="top" class="domain_mapping_row">
		<td class="http_scheme">
			<span class="label">http://</span>
		</td>
		<td class="http_domain">
			<input type="text" name="http_domain[]" value="" />
		</td>
		<td class="arrow">
			<span class="label">&gt;</span>
		</td>
		<td class="https_scheme">
			<span class="label">https://</span>
		</td>
		<td class="https_domain">
			<input type="text" name="https_domain[]" value="" />
		</td>
		<td class="controls">
			<a class="remove" href="#" title="Remove URL Filter">Remove</a>
			<a class="add" href="#" title="Add URL Filter">Add</a>
		</td>
	</tr>
</table>

<input type="hidden" name="action" value="settings-save" />

<p class="button-controls">
	<input type="submit" name="domain_mapping-save" value="Save Changes" class="button-primary" id="domain_mapping-save" />
	<input type="submit" name="domain_mapping-reset" value="Reset" class="button-secondary" id="domain_mapping-reset" />
	<img alt="Waiting..." src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting submit-waiting" />
</p>
</form>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#<?php echo $this->getPlugin()->getSlug(); ?>_domain_mapping_form').submit(function() {
		$('#<?php echo $this->getPlugin()->getSlug(); ?>_domain_mapping_form .submit-waiting').show();
	}).ajaxForm({
		data: { ajax: '1'},
		success: function(responseText, textStatus, XMLHttpRequest) {
			$('#<?php echo $this->getPlugin()->getSlug(); ?>_domain_mapping_form .submit-waiting').hide();
			$('#message-body').html(responseText).fadeOut(0).fadeIn().delay(5000).fadeOut();
		}
	});

	if ( $('#domain_mapping tr').length <= 1 ) {
		$('#domain_mapping .remove').hide();
	} else {
		$('#domain_mapping .remove').show();
		$('#domain_mapping .add').hide();
		$('#domain_mapping tr:last-child .add').show();
	}

	$('.domain_mapping_row .add').live('click', function(e) {
		e.preventDefault();
		var row = $(this).parents('tr').clone();
		row.find('input').val('');
		$(this).parents('table').append(row);
		$(this).hide();
		$('#domain_mapping .remove').show();
		return false;
	});

	$('.domain_mapping_row .remove').live('click', function(e) {
		e.preventDefault();
		$(this).parents('tr').remove();
		if ( $('#domain_mapping tr').length <= 1 ) {
			$('#domain_mapping .remove').hide();
		} else {
			$('#domain_mapping .remove').show();
		}
		$('#domain_mapping .add').hide();
		$('#domain_mapping tr:last-child .add').show();
		return false;
	});

	$('#domain_mapping-reset').click(function(e, el) {
	   if ( ! confirm('Are you sure you want to reset all WordPress HTTPS domain mappings?') ) {
			e.preventDefault();
			return false;
	   }
	});
});
</script>