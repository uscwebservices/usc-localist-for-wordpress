<?php
/**
 * Settings Instructions: Admin Notes
 *
 * @package	Usc<em>Localist</em>For_Wordpress
 * @subpackage Usc<em>Localist</em>For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */

?>

<h3 id="notes-events">Events</h3>

<h4 id="notes-events-event-departments">Event Departments</h4>

<p>Once a department, or group of departments, is chosen, events from the department(s) will be selected and then the other search parameters applied.  This is pertinent to <code>keyword</code> and <code>type</code> searches with a setting of <code>match=any</code>.</p>

<strong>Example</strong>

<p>We have a 'History' department <code>group_id=1</code> with 20 events and 10 tagged with the keyword 'History'.  In all of USC events, we have 30 events tagged with the keyword 'History'.</p>

<table class="widefat">
	<thead>
		<td><strong>Dept ID</strong></td>
		<td><strong>Dept Name</strong></td>
		<td><strong>Tag</strong></td>
		<td><strong>Number of Events</strong></td>
	</thead>
	<tr>
		<td rowspan="2">1</td>
		<td rowspan="2">History</td>
		<td>n/a</td>
		<td>10</td>
	</tr>
	<tr>
		<td>'History'</td>
		<td>10</td>
	</tr>
	<tr>
		<td><em>Multiple</em></td>
		<td><em>Multiple</em></td>
		<td>'History'</td>
		<td>30</td>
	</tr>
</table>

<p>Searching for the following elements separately:</p>

	<p><code>[localist-calendar get='events' group_id='1'] produces 20 results</code></p>

	<p><code>[localist-calendar get='events' keyword='History'] produces 30 results</code></p>

<p>If we combined the two searches above with match 'any', we might expect to get 50 results:</p>

	<code>[localist-calendar get='events' group_id='1' keyword='History' match='any']</code>

<p>However, since the events for the department 'History' are gathered first and then the filters applied, we would only get 10 events in return and not 50.</p>

<p>Please see the <a href="http://www.localist.com/doc/api">Localist API</a> for the latest information.</p>

<p><a class="button button-primary" href="http://www.localist.com/doc/api">Latest Localist API Documentation</a></p>
