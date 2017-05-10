<?php
/**
 * Settings Instructions: Shortcode API Options
 *
 * @package	USC Localist For_Wordpress
 * @subpackage USC Localist For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */

?>
<h3 id="shortcode-api-options">Shortcode API Options</h3>

<p>This is a WordPress Plugin that uses the shortcode <code>[localist-calendar]</code> to get events from the <a href="https://calendar.usc.edu">USC Calendar</a>.</p>

<p>Below are the attributes available from the Localist API. Please reference the <a href="https://www.localist.com/doc/api">Localist API Documents</a> for full documentation of currently supported options.</p>

<p><a class="button button-primary" href="https://www.localist.com/doc/api">See Localist API Documents</a></p>

<table class="widefat">
	<thead>
		<td><strong>Location</strong></td>
		<td><strong>Date</strong></td>
		<td><strong>Page</strong></td>
		<td><strong>Filter</strong></td>
		<td><strong>Output</strong></td>
	</thead>
	<tr>
		<td>
			<ul>
				<li><code>bounds</code></li>
				<li><code>campus_id</code></li>
				<li><code>group_id</code></li>
				<li><code>near</code></li>
				<li><code>units</code></li>
				<li><code>venue_id</code></li>
				<li><code>within</code></li>
			</ul>
		</td>
		<td>
			<ul>
				<li><code>days</code></li>
				<li><code>end</code></li>
				<li><code>start</code></li>
			</ul>
		</td>
		<td>
			<ul>
				<li><code>page</code></li>
				<li><code>pp</code></li>
			</ul>
		</td>
		<td>
			<ul>
				<li><code>created_after</code></li>
				<li><code>created_before</code></li>
				<li><code>exclude_type</code></li>
				<li><code>featured</code></li>
				<li><code>keyword</code></li>
				<li><code>match</code></li>
				<li><code>recurring</code></li>
				<li><code>require_all</code></li>
				<li><code>sponsored</code></li>
				<li><code>type</code></li>
			</ul>
		</td>
		<td>
			<ul>
				<li><code>all_custom_fields</code></li>
				<li><code>direction</code></li>
				<li><code>distinct</code></li>
				<li><code>include_attendance</code></li>
				<li><code>sort</code></li>
			</ul>
		</td>
	</tr>
</table>
