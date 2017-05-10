<?php
/**
 * Settings Instructions: Custom Shortcode API Options
 *
 * @package	USC Localist For_Wordpress
 * @subpackage USC Localist For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */

?>
<h3 id="settings-bookmark-custom-shortcode-api-options">Custom Shortcode API options</h3>

<p>In addition to the attributes from the <a href="https://www.localist.com/doc/api">Localist API</a>, the following custom attributes can be used.</p>

<table class="widefat">
	<thead>
		<td><strong>Parameter</strong></td>
		<td><strong>Type</strong></td>
		<td><strong>Options</strong></td>
		<td><strong>Default</strong></td>
		<td><strong>Description</strong></td>
	</thead>
	<tbody>
		<tr>
			<td><code>get</code></td>
			<td>string</td>
			<td>
				<code>events</code><br>
				<code>event</code>
			</td>
			<td>
				<code>events</code>
			</td>
			<td>The <code>type</code> of API data to get.</td>
		</tr>
		<tr>
			<td><code>cache</code></td>
			<td>integer</td>
			<td></td>
			<td>1 hour</td>
			<td>
				The amount of time (in seconds) to store the API results in the site.  This will help performance of the page.
				<br><strong>Note:</strong> setting the cache to <code>0</code> sets the cache to never be set. (<a href="#note-cache">see note<sup>1</sup> below</a>)
			</td>
		</tr>
		<tr>
			<td><code>date_range</code></td>
			<td>boolean</td>
			<td>
				<code>true</code><br>
				<code>false</code>
			</td>
			<td>
				<code>false</code>
			</td>
			<td>Displays <code>first_date</code> - <code>last_date</code> on multiple <code>events</code> if dates differ, else the single instance will display. (<a href="#note-date-range">see note<sup>2</sup> below</a>)</td>
		</tr>
		<tr>
			<td><code>details_page</code></td>
			<td>string</td>
			<td></td>
			<td>false</td>
			<td>Enter the link to the events detail page. Global setting available in the <a href="<?php echo esc_url( admin_url( 'customize.php' ) );?>">Customizer</a> options. Please see <code>is_events_page</code>.</td>
		</tr>
		<tr>
			<td><code>is_events_page</code></td>
			<td>boolean</td>
			<td>
				<code>true</code> <br>
				<code>false</code>
			</td>
			<td>
				<code>false</code>
			</td>
			<td>Displays multiple events and single event details using the same shortcode on the same page.</td>
		</tr>
		<tr>
			<td><code>paginate</code></td>
			<td>string</td>
			<td>
				<code>next</code><br>
				<code>numeric</code>
			</td>
			<td>
				false
			</td>
			<td>
				Show the pagination on multiple events api.
				<br><code>next</code> displays << Prev Next >>
				<br><code>numeric</code> displays 1 2 3
			</td>
		</tr>
		<tr>
			<td><code>paginate_offset</code></td>
			<td>numeric</td>
			<td></td>
			<td><code>3</code></td>
			<td>
				The amount of numbers to show before and after the current page.
				<br>Example using <code>3</code>: 1...10 <strong>11</strong> 12...95
			</td>
		</tr>
		<tr>
			<td><code>paginate_numeric_separator</code></td>
			<td>string</td>
			<td><code> ... </code></td>
			<td>false</td>
			<td>
				The separator used between first, last and the offset page start/end.
				<br>Example: 1 ... 10 <strong>11</strong> 12 ... 95
			</td>
		</tr>
		<tr>
			<td><code>template_multiple</code></td>
			<td>string</td><td></td>
			<td>
				<code>events-list.html</code>
			</td>
			<td>The <code>slug</code> of the post type <a href="<?php esc_url( admin_url( 'edit.php?post_type=event-template' ) );?>">Event Templates</a> to use for the structure of the returned API data for a list of events.  Defaults to <a href="<?php echo esc_url( admin_url( 'options-general.php?page=usc-localist-for-wordpress-admin&tab=templates#templates-samples-multiple' ) );?>">list view</a>.</td>
		</tr>
		<tr>
			<td><code>template_single</code></td>
			<td>string</td>
			<td></td>
			<td>
				<code>events-single.html</code>
			</td>
			<td>Enter the <code>slug</code> of the post type <a href="<?php esc_url( admin_url( 'edit.php?post_type=event-template' ) );?>">Event Templates</a> to use for the structure of the returned API data for a single event.  Defaults to <a href="<?php echo esc_url( admin_url( 'options-general.php?page=usc-localist-for-wordpress-admin&tab=templates#templates-samples-single' ) );?>">single view</a>.</td>
		</tr>
		<tr>
			<td><code>message_no_events</code></td>
			<td>string</td>
			<td></td>
			<td>
				<code>No scheduled events.</code>
			</td>
			<td>Enter the message to display if there are no events returned from the API. (<a href="#note-no-events-message">see <sup>3</sup> below</a>)</td>
		</tr>
	</tbody>
</table>

<p id="note-cache"><strong><sup>1</sup>Note:</strong> Setting a value for <code>cache</code> means that the database will store a transient object value of returned data.  The recommended minimum for this should be 900 (15 minutes).  Do not set numbers less than a minute as this may over task the database writing transients unnecessarily.  Object cache is separate from page cache.  If a page cache is set to a time less than the <code>cache</code>, it may take the page cache time plus the object cache time to refresh the data displayed.</p>

<p id="note-date-range"><strong><sup>2</sup>Note:</strong> The shortcode attribute <code>date-range</code> will only show on multiple events list.  Single event details will list all instances of dates after current date.</p>

<p id="note-no-events-message"><strong><sup>3</sup>Note:</strong> The output of the no events message is <code>&lt;p class="no-events-message"&gt;No scheduled events.&lt;/p&gt;</code></p>
