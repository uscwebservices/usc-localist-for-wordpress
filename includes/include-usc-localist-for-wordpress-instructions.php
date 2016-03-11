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
	pre {
		padding: 1rem;
		background: #23282d;
		color: #fff;
	}
</style>
<div class="wrap">
	
	<h2 id="settings-bookmark-usc-localist-for-wordpress">USC Localist for WordPress</h2>

	<ul>
		<li><a href="#settings-bookmark-plugin-usage">Plugin Usage</a></li>
		<li><a href="#settings-bookmark-api-options">API Options</a></li>
		<li><a href="#settings-bookmark-custom-shortcode-api-options">Custom shortcode API options</a></li>
		<li><a href="#settings-bookmark-customizer">Customizer</a></li>
		<li><a href="#settings-bookmark-templates">Templates</a></li>
		<li><a href="#settings-bookmark-notes">Notes</a></li>
	</ul>
	
	<h3 id="settings-bookmark-plugin-usage">Plugin Usage</h3>

	<p>This is a WordPress Plugin that uses the shortcode <code>[localist-calendar]</code> to get events from the <a href="https://calendar.usc.edu">USC Calendar</a>.</p>


	<h3 id="settings-bookmark-api-options">API Options</h3>

	<p>Please reference the <a href="http://www.localist.com/doc/api">Localist API</a> for a full reference of current supported options.</p>

	<h3 id="settings-bookmark-custom-shortcode-api-options">Custom Shortcode API options</h3>

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
					<br><strong>Note:</strong> setting the cache to <code>0</code> sets the cache to never expire.
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
				<td>Displays <code>first_date</code> - <code>last_date</code> on <code>events</code> if dates differ else the next single instance will display. (see note below)</td>
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
				<td>Uses the same page for details page.</td>
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
				<td>Show the pagination on multiple events api.</td>
			</tr>
			<tr>
				<td><code>paginate_offset</code></td>
				<td>numeric</td>
				<td></td>
				<td><code>3</code></td>
				<td>The amount of numbers to show before and after the current page.</td>
			</tr>
			<tr>
				<td><code>paginate_numeric_separator</code></td>
				<td>string</td>
				<td><code> ... </code></td>
				<td>false</td>
				<td>The separator used betwen first, last and the offset page start/end. <br> Example: 1 ... 21 <strong>22</strong> 23 ... 84</td>
			</tr>
			<tr>
				<td><code>template_multiple</code></td>
				<td>string</td><td></td>
				<td>
					<code>events-list.html</code>
				</td>
				<td>The <code>slug</code> of the post type <strong>Event Templates</strong> to use for the structure of the returned API data for a list of events.  Defaults to list view.</td>
			</tr>
			<tr>
				<td><code>template_single</code></td>
				<td>string</td>
				<td></td>
				<td>
					<code>events-single.html</code>
				</td>
				<td>Enter the <code>slug</code> of the posty type <strong>Event Templates</strong> to use for the structure of the returned API data for a single event.  Defaults to single view.</td>
			</tr>
		</tbody>
	</table>

	<p><strong>Note:</strong> the shortcode attribute <code>date-range</code> will only show on multiple events list.  Single event details will list all instances of dates after current date.</p>

	<h3 id="settings-bookmark-customizer">Customizer</h3>

	<p>This plugin uses the WordPress <a href="/wp-admin/customize.php">Customizer</a> to set global calendar settings for the following items.</p>

	<table class="widefat">
		<thead>
			<td><strong>Option</strong></td>
			<td><strong>Output</strong></td>
		</thead>
		<tr>
			<td><code>no</code></td>
			<td>1/1/2016, 2/1/2016, 3/1/2016</td>
		</tr>
		<tr>
			<td><code>yes</code></td>
			<td>1/1/2016 - 3/1/2016</td>
		</tr>
	</table>


	<h4 id="settings-bookmark-customizer-event-details-page">Event Details Page</h4>

	<p>Choose a page where the events link to an event details page.</p>

	<p>On the selected page, you must use the shortcode: </p>

		<code>[localist-calendar get="event"]</code>

	<p>Or, to display events and the event detail with one shortcode:</p>

		<p><code>[localist-calendar get="events" is_events_page="true"]</code></p>

	<p>If you leave the dropdown blank, the event links will go to the event detail page on the <a href="https://calendar.usc.edu">USC Calendar</a>.</p>


	<h3 id="settings-bookmark-templates">Templates</h3>

	<p>You can add a custom template in the <a href="edit.php?post_type=event-template">Events Templates</a> section and specify it's use within the shortcode as <code>template_multiple</code> for events lists or <code>template_sinle</code> for event details pages.</p>

	<p>Please use the sections below to help write a template using data attributes.</p>

	<h4 id="settings-bookmark-templates-data-fields">Data Fields</h4>

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


	<h4 id="settings-bookmark-templates-links">Links</h4>

	<p>To set a link from the API data, you can add the data attribute <code>data-link</code> to an <code>a</code> tag and use the mapped dot syntax path to the data which will set the <code>src</code> attribute of the tag.</p>

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
				<td>The dot syntax mapping to the url.</td>
			</tr>
			<tr>
				<td><code>map</code></td>
				<td>Automatically sets link to <code>location_name</code>.</td>
			</tr>
			<tr>
				<td><code>detail</code></td>
				<td>Automatically sets link to event detaill page.</td>
			</tr>
		</tbody>
	</table>

	<p>You can use this in conjunction with the <code>data-field</code> to set the text of the link.</p>

	<p>Sample:</p>

<pre>
event: {
	title: "USC Tommy Trojan",
	localist_url: "http://calendar.usc.edu/event/usc_tommy_trojan",
	location_name: "Student Union (STU)"
}
</pre>
	
	<p>Using the sample data:</p>

<pre>
&lt;a href=&quot;&quot; data-link=&quot;localist_url&quot; data-field=&quot;title&quot;&gt;&lt;/a&gt;
&lt;a class=&quot;event-map&quot; href=&quot;&quot; data-link=&quot;map&quot; data-field=&quot;location_name&quot;&gt;&lt;/a&gt;
</pre>

	<p>Would output:</p>

<pre>
&lt;a href=&quot;http://calendar.usc.edu/event/usc_tommy_trojan&quot; data-link=&quot;localist_url&quot; data-field=&quot;title&quot;&gt;USC Tommy Trojan&lt;/a&gt;
&lt;a class=&quot;event-map&quot; href=&quot;http://web-app.usc.edu/maps/?b=STU&quot; data-link=&quot;map&quot; data-field=&quot;location_name&quot;&gt;Student Union (STU)&lt;/a&gt;
</pre>



	<p><strong>Note:</strong> The <code>data-link="map"</code> function will set the link to the three letter code at the end of the location name. Leavey Library (LVL) will link to the UPC map for <em>LVL</em>.  Any three letter codes for HSC will link to the HSC map.  IF there is no three letter code, the link will go to the UPC maps with a query parameter of the <code>location_name</code>.</p>


	<h4 id="settings-bookmark-templates-dates">Dates</h4>

	<p>To set a date ore time setting from the API data, you can add the data attribute <code>data-date-type</code> to any HTML tag.  This will automatically map the data to the <code>first_date</code>, <code>last_date</code>, or event instance(s) depending on the options chosen.</p>

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
				<td>Returns the date and time of the selection. Use with <code>data-format-time</code> and <code>data-format-date</code>.</td>
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
				<td>Uses the <code>start</code> and <code>end</code> of the event instance for the date/time output. Uses <code>data-format-time</code> and <code>data-format-date</code>.</td>
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
				<td><code>data-separator</code></td>
				<td>string</td>
				<td></td>
				<td></td>
				<td>Used between date/time instances for single events.</td>
			</tr>
		</tbody>
	</table>

	<p>Using the sample data:</p>

<pre>
event: {
	first_date: "2016-02-02",
	last_date: "2016-04-05",
	event_instances: [
		{
			event_instance: {
				start: "2016-03-08T10:45:00-08:00",
				end: "2016-03-08T11:45:00-08:00"
			}
		},
		{
			event_instance: {
				start: "2016-03-22T10:45:00-07:00",
				end: "2016-03-22T11:45:00-07:00"
			}
		},
		{
			event_instance: {
				start: "2016-03-29T10:45:00-07:00",
				end: "2016-03-29T11:45:00-07:00"
			}
		},
		{
			event_instance: {
				start: "2016-04-05T10:45:00-07:00",
				end: "2016-04-05T11:45:00-07:00"
			}
		}
	]
}
</pre>
	
	<p>The code below:</p>
<pre>
&lt;!-- 1 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;date&quot;&gt;&lt;/div&gt;

&lt;!-- 3 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;time&quot; date-instance=&quot;start&quot;&gt;&lt;/div&gt;

&lt;!-- 4 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;time&quot; date-instance=&quot;end&quot;&gt;

&lt;!-- 5 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;datetime-start-end&quot; data-format-date=&quot;l, F jS, Y&quot; data-separator=&quot;&lt;br&gt;&quot;&gt;&lt;/div&gt;
</pre>

	<p>Would output:</p>

<pre>
&lt;!-- 1 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;date&quot;&gt;
	&lt;time datetime=&quot;2016-03-08T10:45:00-08:00&quot;&gt;03/08/2016&lt;/time&gt;
	&lt;time datetime=&quot;2016-03-15T10:45:00-07:00&quot;&gt;03/15/2016&lt;/time&gt;
	&lt;time datetime=&quot;2016-03-22T10:45:00-07:00&quot;&gt;03/22/2016&lt;/time&gt;
	&lt;time datetime=&quot;2016-03-29T10:45:00-07:00&quot;&gt;03/29/2016&lt;/time&gt;
	&lt;time datetime=&quot;2016-04-05T10:45:00-07:00&quot;&gt;04/05/2016&lt;/time&gt;
&lt;/div&gt;

&lt;!-- 3 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;time&quot; date-instance=&quot;start&quot;&gt;
	&lt;time&gt;6:45 pm&lt;/time&gt;
	&lt;time&gt;5:45 pm&lt;/time&gt;
	&lt;time&gt;5:45 pm&lt;/time&gt;
	&lt;time&gt;5:45 pm&lt;/time&gt;
	&lt;time&gt;5:45 pm&lt;/time&gt;
&lt;/div&gt;

&lt;!-- 4 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;time&quot; date-instance=&quot;end&quot;&gt;
	&lt;time&gt;6:45 pm&lt;/time&gt;
	&lt;time&gt;5:45 pm&lt;/time&gt;
	&lt;time&gt;5:45 pm&lt;/time&gt;
	&lt;time&gt;5:45 pm&lt;/time&gt;
	&lt;time&gt;5:45 pm&lt;/time&gt;
&lt;/div&gt;

&lt;!-- 5 --&gt;
&lt;div class=&quot;event-dates&quot; data-date-type=&quot;datetime-start-end&quot; data-format-date=&quot;l, F jS, Y&quot; data-separator=&quot;&lt;br&gt;&quot;&gt;
	&lt;time datetime=&quot;2016-03-08T10:45:00-08:00&quot;&gt;Tuesday, March 8th, 2016 at 6:45 pm to 7:45 pm&lt;/time&gt;&lt;br&gt;
	&lt;time datetime=&quot;2016-03-15T10:45:00-07:00&quot;&gt;Tuesday, March 15th, 2016 at 5:45 pm to 6:45 pm&lt;/time&gt;&lt;br&gt;
	&lt;time datetime=&quot;2016-03-22T10:45:00-07:00&quot;&gt;Tuesday, March 22nd, 2016 at 5:45 pm to 6:45 pm&lt;/time&gt;&lt;br&gt;
	&lt;time datetime=&quot;2016-03-29T10:45:00-07:00&quot;&gt;Tuesday, March 29th, 2016 at 5:45 pm to 6:45 pm&lt;/time&gt;&lt;br&gt;
	&lt;time datetime=&quot;2016-04-05T10:45:00-07:00&quot;&gt;Tuesday, April 5th, 2016 at 5:45 pm to 6:45 pm&lt;/time&gt;
&lt;/div&gt;
</pre>





	<h4 id="settings-bookmark-templates-photos">Photos</h4>

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

	<h5 id="settings-bookmark-templates-photos-photo-format">Photo Format</h5>

	<p>Using <code>data-format</code> with <code>data-photo</code>, you can set the size of the images to be returned from the following list:</p>

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
	</table>


	<h4 id="settings-bookmark-templates-samples">Template Samples</h4>

	<p>Below are the default templates used for the plugin.</p>

	<h5 id="settings-bookmark-templates-samples-multiple">Multiple Events Template</h5>

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

	<h5 id="settings-bookmark-templates-samples-single">Single Event Template</h5>

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



	<h3 id="settings-bookmark-notes">Notes</h3>


	<h4 id="settings-bookmark-notes-events">Events</h4>

	<h5 id="settings-bookmark-notes-events-event-departments">Event Departments</h5>

	<p>Once a department, or group of departments, is chosen, events from the department(s) will be selected and then the other search parameters applied.  This is pertinent to <code>keyword</code> and <code>type</code> searches with a setting of <code>match=any</code>.</p>

	**Example**

	<p>We have a 'History' department (group_id=1) with 20 events and 10 tagged with the keyword 'History'.  In all of USC events, we have 30 events tagged with the keyword 'History'.</p>

	<p>Searching for the following elements separately:</p>

		<p><code>[localist-calendar get='events' group_id='1'] produces 20 results</code></p>

		<p><code>[localist-calendar get='events' keyword='History'] produces 30 results</code></p>

	<p>If we combined the two searches above with match 'any', we might expect to get 50 results:</p>

		<code>[localist-calendar get='events' group_id='1' keyword='History' match='any']</code>

	<p>However, since the events for the department 'History' are gathered first and then the filters applied, we would only get 10 events in return and not 50.</p>

	<p>Please see the <a href="http://www.localist.com/doc/api">Localist API</a> for the latest information.</p>

