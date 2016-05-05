<?php 
/**
 * The Settings Instructions.
 *
 * @package	Usc<em>Localist</em>For_Wordpress
 * @subpackage Usc<em>Localist</em>For_Wordpress/includes
 * @author	 USC Web Services <webhelp@usc.edu>
 */
?>
<style type="text/css">
	
	/* headings */
	.usc_lfwp_wrap h1 { font-size: 2.5rem; }
	.usc_lfwp_wrap h2 { font-size: 2.25rem; }
	.usc_lfwp_wrap h3 { font-size: 1.75rem; }
	.usc_lfwp_wrap h4 { font-size: 1.3rem; }
	.usc_lfwp_wrap h5 { font-size: 1.0rem; }
	.usc_lfwp_wrap h6 { font-size: 0.7rem; }
	
	/* code style */
	.usc_lfwp_wrap pre {
		padding: 1rem;
		background: #23282d;
		color: #fff;
		white-space: pre-wrap;		/* css-3 */
		white-space: -moz-pre-wrap;	/* Mozilla, since 1999 */
		white-space: -pre-wrap;		/* Opera 4-6 */
		white-space: -o-pre-wrap;	/* Opera 7 */
		word-wrap: break-word;		/* Internet Explorer 5.5+ */
	}

	/* list style */
	.usc_lfwp_wrap ul {
		padding: .25rem 0 0 1rem;
	}

	.usc_lfwp_wrap table ul {
		padding: 0;
	}

</style>
<div class="usc_lfwp_wrap">
	
	<h1 id="settings-bookmark-usc-localist-for-wordpress">USC Localist for WordPress</h1>

	<ul>
		<li><a href="#settings-bookmark-plugin-usage">Plugin Usage</a></li>
		<li><a href="#settings-bookmark-shortcode-api-options">Shortcode API Options</a>
			<ul>
				<li><a href="#settings-bookmark-shortcode-api-options-locality">Locality</a></li>
			</ul>
		</li>
		<li><a href="#settings-bookmark-custom-shortcode-api-options">Custom shortcode API options</a></li>
		<li><a href="#settings-bookmark-customizer">Customizer</a>
			<ul>
				<li><a href="#settings-bookmark-customizer-event-dates-range">Dates Range</a></li>
				<li><a href="#settings-bookmark-customizer-event-details-page">Event Details Page</a></li>
			</ul>
		</li>

		<li><a href="#settings-bookmark-templates">Templates</a>
			<ul>
				<li><a href="#settings-bookmark-templates-data-fields">Data Fields</a></li>
				<li><a href="#settings-bookmark-templates-links">Links</a></li>
				<li><a href="#settings-bookmark-templates-dates">Dates</a></li>
				<li><a href="#settings-bookmark-templates-photos">Photos</a>
					<ul>
						<li><a href="#settings-bookmark-templates-photos-photo-format">Photo Format</a></li>
					</ul>
				</li>
				<li><a href="#settings-bookmark-templates-samples">Template Samples</a>
					<ul>
						<li><a href="#settings-bookmark-templates-samples-multiple">Multiple Events Template</a></li>
						<li><a href="#settings-bookmark-templates-samples-single">Single Events Template</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li><a href="#settings-bookmark-notes">Notes</a>
			<ul>
				<li><a href="#settings-bookmark-notes-events">Events</a>
					<ul>
						<li><a href="#settings-bookmark-notes-events-event-departments">Event Departments</a></li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>
	
	<h2 id="settings-bookmark-plugin-usage">Plugin Usage</h2>

	<p>This is a WordPress Plugin that uses the shortcode <code>[localist-calendar]</code> to get events from the <a href="https://calendar.usc.edu">USC Calendar</a>.</p>


	<h2 id="settings-bookmark-shortcode-api-options">Shortcode API Options</h2>
	
	<p>Below are the attributes available from the Localist API. Please reference the <a href="http://www.localist.com/doc/api">Localist API Documents</a> for full documentation of currently supported options.</p>

	<p><a class="button button-primary" href="http://www.localist.com/doc/api">See Localist API Documents</a></p>

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

	<h2 id="settings-bookmark-custom-shortcode-api-options">Custom Shortcode API options</h2>

	<p>In addion to the attributes from the <a href="http://www.localist.com/doc/api">Localist API</a>, the following custom attributes can be used.</p>

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
				<td>Enter the link to the events detail page. Global setting available in the <a href="#settings-bookmark-customizer">Customizer</a> options. Please see <code>is_events_page</code>.</td>
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
					The separator used betwen first, last and the offset page start/end. 
					<br>Example: 1 ... 10 <strong>11</strong> 12 ... 95
				</td>
			</tr>
			<tr>
				<td><code>template_multiple</code></td>
				<td>string</td><td></td>
				<td>
					<code>events-list.html</code>
				</td>
				<td>The <code>slug</code> of the post type <a href="edit.php?post_type=event-template">Event Templates</a> to use for the structure of the returned API data for a list of events.  Defaults to <a href="#settings-bookmark-templates-samples-multiple">list view</a>.</td>
			</tr>
			<tr>
				<td><code>template_single</code></td>
				<td>string</td>
				<td></td>
				<td>
					<code>events-single.html</code>
				</td>
				<td>Enter the <code>slug</code> of the posty type <a href="edit.php?post_type=event-template">Event Templates</a> to use for the structure of the returned API data for a single event.  Defaults to <a href="#settings-bookmark-templates-samples-single">single view</a>.</td>
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

	<p id="note-cache"><strong><sup>1</sup>Note:</strong> Setting a value for <code>cache</code> means that the database will store a transient object value of returned data.  The recommended minimum for this should be 900 (15 minutes).  Do not set numbers less than a minute as this may overtask the database writing transients unnecessarily.  Object cache is separate from page cache.  If a page cache is set to a time less than the <code>cache</code>, it may take the page cache time plus the object cache time to refresh the data displayed.</p>

	<p id="note-date-range"><strong><sup>2</sup>Note:</strong> The shortcode attribute <code>date-range</code> will only show on multiple events list.  Single event details will list all instances of dates after current date.</p>

	<p id="note-no-events-message"><strong><sup>3</sup>Note:</strong> The output of the no events message is <code>&lt;p class="no-events-message"&gt;No scheduled events.&lt;/p&gt;</code></p>

	<h2 id="settings-bookmark-customizer">Customizer</h2>

	<p>This plugin uses the WordPress <a href="/wp-admin/customize.php">Customizer</a> to set global calendar settings for the following items.</p>
	
	<h3 id="settings-bookmark-customizer-event-dates-range">Dates Range</h3>

	<p>Display multiple dates as a range on multiple events list.</p>

	<table class="widefat">
		<thead>
			<td><strong>Option</strong></td>
			<td><strong>Output</strong></td>
		</thead>
		<tr>
			<td><code>no</code></td>
			<td>1/1/2016</td>
		</tr>
		<tr>
			<td><code>yes</code></td>
			<td>1/1/2016 - 3/1/2016</td>
		</tr>
	</table>

	<p><strong>Note:</strong> Date ranges will display on multiple event lists only.  Single event details iterate through the dates.  If set to <code>no</code>, the multiple display will show the single date of the current instance returned.</p>

	<p><strong>Note:</strong> Please reference <a href="#settings-bookmark-templates-dates">Templates &gt; Dates</a> for setting a custom value for the range separator.</p>


	<h3 id="settings-bookmark-customizer-event-details-page">Event Details Page</h3>

	<p>Choose a page where the events link to an event details page.</p>

	<p>On the selected page, you must use the shortcode: </p>

		<code>[localist-calendar get="event"]</code>

	<p>Or, to display events and the event detail with one shortcode:</p>

		<p><code>[localist-calendar get="events" is_events_page="true"]</code></p>

	<p>If you leave the dropdown blank, the event links will go to the event detail page on the <a href="https://calendar.usc.edu">USC Calendar</a>.</p>


	<h2 id="settings-bookmark-templates">Templates</h2>

	<p>You can add a custom template in the <a href="edit.php?post_type=event-template">Events Templates</a> section and specify it's use within the shortcode as <code>template_multiple</code> for events lists or <code>template_sinle</code> for event details page.</p>

	<p><a class="button button-primary" href="edit.php?post_type=event-template">Edit Custom Events Templates</a></p>

	<p>Please use the sections below to help write a template using data attributes.</p>

	<h3 id="settings-bookmark-templates-data-fields">Data Fields</h3>

	<p>To use the data from the API, you can add the data attribute <code>data-field</code> to any HTML element and use the mapped dot syntax path to the data.  The <code>data-field</code> will start at the individual <code>event</code> level and fill the content area of the selected tag.</p>

	<table class="widefat">
		<thead>
			<td><strong>Parameter</strong></td>
			<td><strong>Type</strong></td>
			<td><strong>Options</strong></td>
			<td><strong>Description</strong></td>
		</thead>
		<tbody>
			<tr>
				<td><code>data-field</code></td>
				<td>string</td>
				<td></td>
				<td>The dot syntax mapping to the API for the desired value.</td>
			</tr>
		</tbody>
	</table>

	<p>Sample:</p>
	
<pre>
event: {
	title: "Reshaping Tradition: Contemporary Ceramics from East Asia",
	geo: {
		street: "46 North Los Robles Avenue",
		city: "Pasadena",
		state: "CA",
		country: "US",
		zip: "91101"
	}
}
</pre>

	<p>Using the sample data:</p>

		<pre>&lt;address data-field=&quot;geo.city&quot;&gt;&lt;/address&gt;</pre>

	<p>Would output:</p>

		<pre>&lt;address data-field=&quot;geo.city&quot;&gt;Pasadena&lt;/address&gt;</pre>


	<h3 id="settings-bookmark-templates-links">Links</h3>

	<p>To set a link from the API data, you can add the data attribute <code>data-link</code> to an <code>a</code> tag and use the mapped dot syntax path to the data which will set the <code>src</code> attribute of the tag.</p>

	<p>You can use this in conjunction with the <code>data-field</code> to set the text of the link.</p>

	<table class="widefat">
		<thead>
			<td><strong>Parameter</strong></td>
			<td><strong>Type</strong></td>
			<td><strong>Options</strong></td>
			<td><strong>Description</strong></td>
		</thead>
		<tbody>
			<tr>
				<td rowspan="3"><code>data-link</code></td>
				<td rowspan="3">string</td>
				<td>string</td>
				<td>The dot syntax mapping to the url. (<a href="#note-link">see note<sup>1</sup> below</a>)</td>
			</tr>
			<tr>
				<td><code>map</code></td>
				<td>Automatically sets link to <code>location_name</code>. (<a href="#note-link-map">see note<sup>2</sup> below</a>)</td>
			</tr>
			<tr>
				<td><code>detail</code></td>
				<td>Automatically sets link to <a href="#settings-bookmark-customizer-event-details-page">event detail</a> page.</td>
			</tr>
		</tbody>
	</table>
	
	<p id="note-link"><strong><sup>1</sup>Note:</strong> If linking to a <code>string</code> mapped node and there is no link returned, the anchor <code>&lt;a&gt;</code> tag will be changed to a <code>&lt;span&gt;</code> tag and the <code>href</code> attribute removed.</p>

	<p id="note-link-map"><strong><sup>2</sup>Note:</strong> The <code>data-link="map"</code> function will set the link to the three letter code at the end of the location name. Leavey Library (LVL) will link to the UPC map for <em>LVL</em>.  Any three letter codes for HSC will link to the HSC map.  If there is no three letter code, the link will go to the UPC maps with a query parameter of the <code>location_name</code>.  The link will fallback to the following nodes for information:</p>

	<ol>
		<li>USC Maps: 3 letter code in (parenthesis) from <code>location_name</code></li>
		<li>Google Maps: <code>geo.street</code> + <code>geo.city</code> + <code>geo.state</code></li>
		<li>Google Maps: <code>geo.latitude</code> + <code>geo.longitude</code></li>
		<li>USC Maps (UPC with query): <code>location_name</code></li>
		<li>Google Maps: <code>address</code></li>
	</ol>

	<p>Sample data:</p>

<pre>
event: {
	title: "USC Tommy Trojan",
	localist_url: "http://calendar.usc.edu/event/usc_tommy_trojan",
	location_name: "Student Union (STU)",
	ticket_url: "http://eventbrite.com/",
	venue_url: ""
}
</pre>
	
	<p>Template code:</p>

<pre>
&lt;a href=&quot;&quot; data-link=&quot;localist_url&quot; data-field=&quot;title&quot;&gt;&lt;/a&gt;
&lt;a class=&quot;event-map&quot; href=&quot;&quot; data-link=&quot;map&quot; data-field=&quot;location_name&quot;&gt;&lt;/a&gt;
&lt;a class=&quot;ticket&quot; href=&quot;&quot; data-link=&quot;ticket_url&quot;&gt;Ticket&lt;/a&gt;
&lt;a class=&quot;ticket&quot; href=&quot;&quot; data-link=&quot;venue_url&quot; data-field=&quot;location_name&quot;&gt;&lt;/a&gt;
</pre>

	<p>Output:</p>

<pre>
&lt;a href=&quot;http://calendar.usc.edu/event/usc_tommy_trojan&quot; data-link=&quot;localist_url&quot; data-field=&quot;title&quot;&gt;USC Tommy Trojan&lt;/a&gt;
&lt;a class=&quot;event-map&quot; href=&quot;http://web-app.usc.edu/maps/?b=STU&quot; data-link=&quot;map&quot; data-field=&quot;location_name&quot;&gt;Student Union (STU)&lt;/a&gt;
&lt;a class=&quot;ticket&quot; href=&quot;http://eventbrite.com/&quot; data-link=&quot;ticket_url&quot;&gt;Ticket&lt;/a&gt;
&lt;span class=&quot;ticket&quot; data-link=&quot;venue_url&quot; data-field=&quot;location_name&quot;&gt;Student Union (STU)&lt;/span&gt;
</pre>


	<h3 id="settings-bookmark-templates-dates">Dates</h3>

	<p>To set a date or time setting from the API data, you can add the data attribute <code>data-date-type</code> to any HTML tag.  This will automatically map the data to the <code>first_date</code>, <code>last_date</code>, or event instance(s) depending on the options chosen.</p>

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
				<td rowspan="3"><code>data-date-type</code></td>
				<td rowspan="3">string</td>
				<td>
					<code>date</code>
				</td>
				<td></td>
				<td>Returns the date of the selection. Use with <code>data-format-date</code>.</td>
			</tr>
			<tr>
				<td>
					<code>time</code>
				</td>
				<td></td>
				<td>Returns the time of the selection. Use with <code>data-format-time</code>.</td>
			</tr>
			<tr>
				<td>
					<code>datetime</code>
				</td>
				<td></td>
				<td>Returns the date and time of the selection. Use with <code>data-format-date</code> and <code>data-format-time</code>.</td>
			</tr>
			<tr>
				<td rowspan="3"><code>data-date-instance</code></td>
				<td rowspan="3">string</td>
				<td><code>start</code></td>
				<td><code>start</code></td>
				<td>Use the <code>start</code> of the event instance for the date/time output.</td>
			</tr>
			<tr>
				<td><code>end</code></td>
				<td></td>
				<td>Use the <code>end</code> of the event instance for the date/time output.</td>
			</tr>
			<tr>
				<td><code>datetime-start-end</code></td>
				<td></td>
				<td>Uses the <code>start</code> and <code>end</code> of the event instance for the date/time output. Uses <code>data-format-date</code> and <code>data-format-time</code>.</td>
			</tr>
			<tr>
				<td><code>data-format-time</code></td>
				<td>string</td>
				<td></td>
				<td><code>g:i a</code></td>
				<td>Set the time output format using <a href="http://php.net/manual/function.date.php">PHP Date</a>.</td>
			</tr>
			<tr>
				<td><code>data-format-date</code></td>
				<td>string</td>
				<td></td>
				<td><code>m/d/Y</code></td>
				<td>Set the date output format using <a href="http://php.net/manual/function.date.php">PHP Date</a>.</td>
			</tr>
			<tr>
				<td><code>data-separator-range</code></td>
				<td>string</td>
				<td></td>
				<td><code> - </code></td>
				<td>Set the separator for multiple events using the <a href="#settings-bookmark-customizer-event-dates-range">range option</a>.</td>
			</tr>
			<tr>
				<td><code>data-separator-date-time</code></td>
				<td>string</td>
				<td></td>
				<td><code> at </code></td>
				<td>Set the separator for instances using <code>data-date-instance="datetime-start-end"</code> between the date and time output.</td>
			</tr>
			<tr>
				<td><code>data-separator-time</code></td>
				<td>string</td>
				<td></td>
				<td><code> to </code></td>
				<td>Set the separator for instances using <code>data-date-instance="datetime-start-end"</code> between the start time and end time output.</td>
			</tr>
			<tr>
				<td><code>data-separator</code></td>
				<td>string</td>
				<td></td>
				<td></td>
				<td>Used between date/time <code>&lt;time&gt;</code>instances for single events.</td>
			</tr>
		</tbody>
	</table>

	<p>Sample data:</p>

<pre>
event: {
	first_date: "2020-02-02",
	last_date: "2020-04-05",
	event_instances: [
		{
			event_instance: {
				start: "2020-03-08T10:45:00-08:00",
				end: "2020-03-08T12:45:00-08:00"
			}
		},
		{
			event_instance: {
				start: "2020-03-22T10:45:00-07:00",
				end: "2020-03-22T12:45:00-07:00"
			}
		},
		{
			event_instance: {
				start: "2020-03-29T10:45:00-07:00",
				end: "2020-03-29T12:45:00-07:00"
			}
		},
		{
			event_instance: {
				start: "2020-04-05T10:45:00-07:00",
				end: "2020-04-05T12:45:00-07:00"
			}
		}
	]
}
</pre>
	
	<p>Template code:</p>
<pre>
&lt;!-- 1 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;date&quot;&gt;&lt;/div&gt;

&lt;!-- 3 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;time&quot; date-instance=&quot;start&quot;&gt;&lt;/div&gt;

&lt;!-- 4 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;time&quot; date-instance=&quot;end&quot;&gt;

&lt;!-- 5 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;datetime-start-end&quot; data-format-date=&quot;l, F jS, Y&quot; data-separator=&quot;&lt;br&gt;&quot;&gt;&lt;/div&gt;

&lt;!-- 6 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;datetime-start-end&quot; data-format-date=&quot;l, F jS, Y&quot; data-separator=&quot;&lt;br&gt;&quot; data-sepatrator-date-time=&quot; from &quot; data-sepatrator-time=&quot; - &quot;&gt;&lt;/div&gt;
</pre>

	<p>Output:</p>

<pre>
<?php include plugin_dir_path( dirname( __FILE__ ) ) . 'sample/sample-output-dates.php'; ?>
</pre>

	<h3 id="settings-bookmark-templates-photos">Photos</h3>

	<p>The data node <code>photo_url</code> will replace the <code>src</code> with the url of the photo.</p>

	<p>To set a photo url from the API data, you can add the data attribute <code>data-photo</code> to an <code>img</code> tag and use the mapped dot syntax path to the data.  Use this in conjunction with the <code>data-format</code> (below) to change the image size returned.</p>

	<table class="widefat">
		<thead>
			<td><strong>Parameter</strong></td>
			<td><strong>Type</strong></td>
			<td><strong>Options</strong></td>
			<td><strong>Description</strong></td>
		</thead>
		<tbody>
			<tr>
				<td><code>data-photo</code></td>
				<td>string</td>
				<td><code>photo_url</code></td>
				<td>The dot syntax mapping to the photo attribute.</td>
			</tr>
			<tr>
				<td><code>data-format</code></td>
				<td>string</td>
				<td>See list below</td>
				<td>Choose from the available photo sizes to set the <code>src</code></td>
			</tr>
		</tbody>
	</table>

	<h4 id="settings-bookmark-templates-photos-photo-format">Photo Format</h4>

	<p>Using <code>data-format</code> with <code>data-photo</code>, you can set the size of the images to be returned from the following list. <code>huge</code> is the default size returned from the API.</p>

	<table class="widefat">
		<thead>
			<td><strong>Value</strong></td>
			<td><strong>Returned Size (px)</strong></td>
		</thead>
		<tr>
			<td><code>tiny</code></td>
			<td>20×20</td>
		</tr>
		<tr>
			<td><code>small</code></td>
			<td>50x50</td>
		</tr>
		<tr>
			<td><code>medium</code></td>
			<td>80×80</td>
		</tr>
		<tr>
			<td><code>big</code></td>
			<td>200×150</td>
		</tr>
		<tr>
			<td><code>big_300</code></td>
			<td>300×225</td>
		</tr>
		<tr>
			<td><code>huge</code></td>
			<td>Uploaded image size</td>
		</tr>
	</table>


	<h3 id="settings-bookmark-templates-samples">Template Samples</h3>

	<p>Below are the default templates used for the plugin.</p>

	<h4 id="settings-bookmark-templates-samples-multiple">Multiple Events Template</h4>

<pre>
&lt;html&gt;
	&lt;article class=&quot;event-item&quot;&gt;
		&lt;h1 class=&quot;event-title&quot;&gt;&lt;a href=&quot;&quot; data-link=&quot;detail&quot; data-field=&quot;title&quot;&gt;&lt;/a&gt;&lt;/h1&gt;
		&lt;div class=&quot;event-dates&quot; data-date-type=&quot;datetime-start-end&quot; data-format-date=&quot;l, F jS, Y&quot; data-separator=&quot;&lt;br&gt;&quot;&gt;&lt;/div&gt;
		&lt;address class=&quot;event-location&quot; data-field=&quot;location_name&quot;&gt;&lt;/address&gt;
		&lt;img src=&quot;&quot; data-photo=&quot;photo_url&quot; data-format=&quot;medium&quot; /&gt;
	&lt;/article&gt;
&lt;/html&gt;
</pre>

	<h4 id="settings-bookmark-templates-samples-single">Single Event Template</h4>

<pre>
&lt;html&gt;
	&lt;article class=&quot;event single&quot;&gt;
		&lt;h1 class=&quot;event-title&quot; data-field=&quot;title&quot;&gt;&lt;/h1&gt;
		&lt;img class=&quot;event-image&quot; src=&quot;&quot; data-photo=&quot;photo_url&quot; data-format=&quot;big&quot; /&gt;
		&lt;div class=&quot;event-dates&quot; data-date-type=&quot;datetime-start-end&quot; data-format-date=&quot;l, F jS, Y&quot; data-separator=&quot;&lt;br&gt;&quot;&gt;&lt;/div&gt;
		&lt;div class=&quot;event-location&quot;&gt;
			&lt;a class=&quot;event-map&quot; href=&quot;&quot; data-link=&quot;map&quot; data-field=&quot;location_name&quot;&gt;&lt;/a&gt;
			&lt;span class=&quot;event-location&quot; data-field=&quot;geo.city&quot;&gt;&lt;/span&gt;
		&lt;/div&gt;
		&lt;div data-field=&quot;description&quot;&gt;&lt;/div&gt;
	&lt;/article&gt;
&lt;/html&gt;
</pre>



	<h2 id="settings-bookmark-notes">Notes</h2>


	<h3 id="settings-bookmark-notes-events">Events</h3>

	<h4 id="settings-bookmark-notes-events-event-departments">Event Departments</h4>

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

