<table>
	<tr>
		<th><?php echo $this->getLang('h_id') ?></th>
		<th><?php echo $this->getLang('h_code') ?></th>
		<th><?php echo $this->getLang('h_name') ?></th>
		<th><?php echo $this->getLang('h_plan_date') ?></th>
		<th><?php echo $this->getLang('h_coordinator') ?></th>
		<th><?php echo $this->getLang('h_assumptions') ?></th>
		<th><?php echo $this->getLang('h_finish_date') ?></th>
		<th><?php echo $this->getLang('h_summary') ?></th>
	</tr>
	<?php while ($row = $this->t['events']->fetchArray()): ?>
		<tr>
			<td><?php echo $row['name'] ?></td>
			<td>
			<?php if ($this->params['confirm_delete'] != $row['name']): ?>
				<a href="?id=
				<?php echo $this->id('categories', 'group',
				$this->params['group'], 'confirm_delete', $row['name']) ?>">
					<?php echo $this->getLang('delete') ?>
				</a>
			<?php else: ?>
				<a href="?id=
				<?php echo $this->id('categories', 'group',
				$this->params['group'], 'delete', $row['name']) ?>">
					<?php echo $this->getLang('approve_delete') ?>
				</a>
			<?php endif ?>
			</td>
		</tr>
	<?php endwhile ?>
</table>
