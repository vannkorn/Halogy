<script type="text/javascript">
function setOrder(){
	$.post('<?php echo site_url('/admin/pages/order/page'); ?>',$(this).sortable('serialize'),function(data){ });
};
function initOrder(el){
	$(el).sortable({ 
		axis: 'y',
	    revert: false, 
	    delay: '80',
	    opacity: '0.5',
	    update: setOrder
	});
};
$(function(){
	$('#collapse').change(function(){
		if ($(this).val() == 'collapse'){
			$('.subpage').slideUp();
		} else if ($(this).val() == 'hidden'){
			$('.hiddenpage').slideUp();
		} else if ($(this).val() == 'drafts'){
			$('.draft').slideUp();
		} else {
			$('.hiddenpage, .subpage, .draft').slideDown();
		}
	});
	$('a.showform').on('click', function(event){showForm(this,event);});
	$('input#cancel').on('click', function(event){hideForm(this,event);});
	initOrder('ol.order, ol.order ol');
});
</script>

			



<div class="large-10 columns body">
<h2>Pages</h2>
	<?php if (in_array('pages_edit', $this->permission->permissions)): ?>	
		<a href="<?php echo site_url('/admin/pages/add'); ?>" class="button green right">Add Page</a>
	<?php endif; ?>
	<ul class="breadcrumbs">
		<li><a href="#">Home</a></li>
		<li class="current"><a href="#">Pages</a></li>
	</ul>
<?php if ($parents): ?>
		<div class="row">
			<div class="large-4 columns dropdown">
				<label for="collapse">Collapse</label> 

				<select id="collapse">
					<option value="all">Show all</option>		
					<option value="hidden">Hide hidden pages</option>
					<option value="collapse">Hide sub-pages</option>		
					<option value="drafts">Hide drafts</option>		
				</select>
			</div>
		</div>
		<hr>
		<?php foreach ($parents as $page): ?>
			<div class="row">
				<div class="large-4 columns">
					<strong><?php echo (in_array('pages_edit', $this->permission->permissions)) ? anchor('/admin/pages/edit/'.$page['pageID'], $page['pageName'], 'class="pagelink"') : $page['pageName']; ?></strong>
					<p>Path: <?php echo $page['uri']; ?></p>				
				</div>
				<div class="large-4 columns">
					<?php if ($page['active']): ?>
							<?php if ($page['redirect']): ?>
							<span class="label redirect">
								Redirect (<?php echo $page['redirect']; ?>)
							</span>
							<?php else: ?>
							<span class="label published">
								<?php if ($page['active'] && $page['datePublished'] > 0 && ($page['newBlocks'] > 0 || $page['newVersions'] > 0)): ?>
									Published (but modified)
								<?php else: ?>
									Published
								<?php endif; ?>
							</span>
								<?php echo (!$page['navigation']) ? ' (hidden)' : ''; ?>
							<?php endif; ?>						
	
					<?php else: ?>
						<span class="label">Draft</span>
						<?php echo (!$page['navigation']) ? ' (hidden)' : ''; ?>
					<?php endif; ?>
					<?php if ($page['active'] && (!$page['newBlocks'] && !$page['newVersions'])): ?>
						<p>Published: <?php echo dateFmt($page['datePublished'], '', '', TRUE); ?>
					<?php else: ?>
						<p>Modified: <?php echo dateFmt($page['dateModified'], '', '', TRUE); ?>
					<?php endif; ?>
					<em>by <?php echo $this->core->lookup_user($page['userID'], TRUE); ?></em></p>
				</div>
				<div class="large-3 columns">
					<ul class="button-group">
						<li><?php echo anchor($page['uri'], 'View', array('class' => 'small button')); ?></li>
						<?php if (in_array('pages_edit', $this->permission->permissions)): ?>
							<li><?php echo anchor('/admin/pages/edit/'.$page['pageID'], 'Edit', array('class' => 'small button')); ?></li>
						<?php endif; ?>
						<?php if (in_array('pages_delete', $this->permission->permissions)): ?>
							<li><?php echo anchor('/admin/pages/delete/'.$page['pageID'], 'Delete', array('class' => 'small button', 'onclick' => 'return confirm(\'Are you sure you want to delete this?\')')); ?></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
				<?php if (isset($children[$page['pageID']]) && $children[$page['pageID']]): ?>
					<?php foreach ($children[$page['pageID']] as $child): ?>
					<div class="row subpage">
						<div class="large-4 columns">
							<span class="padded"><img src="<?php echo $this->config->item('staticPath'); ?>/images/arrow_child.gif" alt="Arrow" /></span> <strong><?php echo (in_array('pages_edit', $this->permission->permissions)) ? anchor('/admin/pages/edit/'.$child['pageID'], $child['pageName'], 'class="pagelink"') : $child['pageName']; ?></strong><br />
							<p>Path: <?php echo $child['uri']; ?></p>
						</div>
						<div class="large-4 columns">
							<?php if ($child['active']): ?>
								<span style="color:green">
									<?php if ($child['redirect']): ?>
										<span class="label redirect">Redirect (<?php echo $child['redirect']; ?>)</span>
									<?php else: ?>
									<?php if ($child['active'] && $child['datePublished'] > 0 && ($child['newBlocks'] > 0 || $child['newVersions'] > 0)): ?>
										<span class="label published">Published (but modified)</span>
									<?php else: ?>
										<span class="label published">Published</span>
									<?php endif; ?>
										<?php echo (!$child['navigation']) ? ' (hidden)' : ''; ?>
									<?php endif; ?>						
								</span>
							<?php else: ?>
								<span class="label">Draft</span>
								<?php echo (!$child['navigation']) ? ' (hidden)' : ''; ?>
							<?php endif; ?>
							<br />
							<?php if ($child['active'] && (!$child['newBlocks'] && !$child['newVersions'])): ?>
								<p>Published: <strong><?php echo dateFmt($child['datePublished'], '', '', TRUE); ?></strong>
							<?php else: ?>
								Modified: <strong><?php echo dateFmt($child['dateModified'], '', '', TRUE); ?></strong>
							<?php endif; ?>
							 by <?php echo $this->core->lookup_user($child['userID'], TRUE); ?></p>
						</div>
						<div class="large-3 columns">
							<ul class="button-group">
									<li><?php echo anchor($child['uri'], 'View', array('class' => 'button small')); ?></li>
								<?php if (in_array('pages_edit', $this->permission->permissions)): ?>
									<li><?php echo anchor('/admin/pages/edit/'.$child['pageID'], 'Edit', array('class' => 'button small')); ?></li>
								<?php endif; ?>
								<?php if (in_array('pages_delete', $this->permission->permissions)): ?>
									<li><?php echo anchor('/admin/pages/delete/'.$child['pageID'], 'Delete', array('class' => 'button small', 'onclick' => 'return confirm(\'Are you sure you want to delete this?\')')); ?></li>
								<?php endif; ?>			
							</ul>				
						</div>
					</div>
				<?php endforeach; ?>
			
				<?php if (isset($subchildren[$child['pageID']]) && $subchildren[$child['pageID']]): ?>
					<?php foreach ($subchildren[$child['pageID']] as $subchild): ?>
						<div class="row">
							<div class="large-4 columns">
								<span class="padded"><img src="<?php echo $this->config->item('staticPath'); ?>/images/arrow_subchild.gif" alt="Arrow" /></span> <strong><?php echo (in_array('pages_edit', $this->permission->permissions)) ? anchor('/admin/pages/edit/'.$subchild['pageID'], $subchild['pageName'], 'class="pagelink"') : $subchild['pageName']; ?></strong>
								<p>Path: <?php echo $subchild['uri']; ?></p>
							</div>
							<div class="large-4 columns">
								<?php if ($subchild['active']): ?>
									<span style="color:green">
										<?php if ($subchild['redirect']): ?>
											<span class="label redirect">Redirect(<?php echo $subchild['redirect']; ?>)</span>
										<?php else: ?>
										<?php if ($subchild['active'] && $subchild['datePublished'] > 0 && ($subchild['newBlocks'] > 0 || $subchild['newVersions'] > 0)): ?>
											<span class="label published">Published (but modified)</span>
										<?php else: ?>
											<span class="label published">Published</span>
										<?php endif; ?>
											<?php echo (!$subchild['navigation']) ? ' (hidden)' : ''; ?>
										<?php endif; ?>						
									</span>
								<?php else: ?>
									<span class="label">Draft</span>
									<?php echo (!$subchild['navigation']) ? ' (hidden)' : ''; ?>
								<?php endif; ?>
								<br />
								<?php if ($subchild['active'] && (!$subchild['newBlocks'] && !$subchild['newVersions'])): ?>
									Published: <strong><?php echo dateFmt($subchild['datePublished'], '', '', TRUE); ?></strong> 
								<?php else: ?>
									Modified: <strong><?php echo dateFmt($subchild['dateModified'], '', '', TRUE); ?></strong> 
								<?php endif; ?>
								<em>by <?php echo $this->core->lookup_user($subchild['userID'], TRUE); ?></em>
							</div>
							<div class="large-3 columns">
								<ul class="button-group">
									<li><?php echo anchor($subchild['uri'], 'View', array('class' => 'button small')); ?></li>
									<?php if (in_array('pages_edit', $this->permission->permissions)): ?>
										<li><?php echo anchor('/admin/pages/edit/'.$subchild['pageID'], 'Edit', array('class' => 'button small')); ?></li>
									<?php endif; ?>
									<?php if (in_array('pages_delete', $this->permission->permissions)): ?>
										<li><?php echo anchor('/admin/pages/delete/'.$subchild['pageID'], 'Delete', array('class' => 'button small', 'onclick' => 'return confirm(\'Are you sure you want to delete this?\')')	); ?></li>
									<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
</div>	

<?php else: ?>

<p class="clear">No pages were found.</p>

<?php endif; ?>
