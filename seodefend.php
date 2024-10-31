<?php
/*
Plugin Name: SEO Defend
Plugin URI: https://seodefend.com
Description: Ongoing protection and monitoring of website and domain assets against negative SEO, black hat SEO techniques and bad SEOs.
Version: 1.4
Author: SEO Defend
Author URI: https://seodefend.com
License: GPL v3
Copyright (C) 2017, SEO Defend - support@seodefend.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Create the function to output the contents of our SEO Defend dashboard Widget

function seodefend_dashboard_widget_function() {

	$risk = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$domain = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$content = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$link = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$algorithm = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';
	$social = '<img src="' . plugins_url( 'img/loadbar.gif', __FILE__ ) . '" alt="Loading..." />';

	$pies1 = '<img src="' . plugins_url( 'img/pies/grey/1.png', __FILE__ ) . '" alt="Risk Factor Rating" />';
	$pies2 = '<img src="' . plugins_url( 'img/pies/lightgreen/2.png', __FILE__ ) . '" alt="Risk Factor  Rating" />';
	$pies3 = '<img src="' . plugins_url( 'img/pies/green/3.png', __FILE__ ) . '" alt="Risk Factor  Rating" />';
	$pies4 = '<img src="' . plugins_url( 'img/pies/lightpurple/4.png', __FILE__ ) . '" alt="Risk Factor  Rating" />';
	$pies5 = '<img src="' . plugins_url( 'img/pies/purple/5.png', __FILE__ ) . '" alt="Risk Factor  Rating" />';

	$status_description = 'Calculating your risk analysis score, refresh this page to see the result.';

	// Create curl resource and retrieve content
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://shield.seodefend.com/api/scan/?key=bGV0bWVpbg&site=' . home_url());
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	
	$json = json_decode($output);
	
	$advice = "";
	
	if(isset($json->{'status'})) {
		switch(true) {
			case ($json->{'status'} >= 0 && $json->{'status'} <= 1) :
				$status_description = 'Request failed. Try again later.';
			break;
			case ($json->{'status'} >= 3 && $json->{'status'} <= 6) :
				$status_description = 'Calculating your risk analysis score, refresh this page to see the result.';
			break;
			case ($json->{'status'} == 7) :
				$scores = true;
				$status_description = 'Finished. We will monitor, free of charge, once every thirty (30) days. Hover over the scores to find out more or <a href="https://seodefend.com/contact" target="_blank">contact us to assist</a>.';
				$risk = $json->{'scores'}->{'risk'};
				if($risk < 10) {
					$advice = "Your current risk analysis score indicates your website is safe. You should ask us to keep monitoring your risk.";
					$risk_bar = 1;
				} elseif($risk < 25) {
					$advice = "Your current risk analysis score indicates a low SEO penalty risk. You should ask us to assist with reducing your risk.";
					$risk_bar = 2;
				} elseif($risk < 50) {
					$advice = "Your current risk analysis score indicates a moderate SEO penalty risk. You should ask us to assist with reducing your risk.";
					$risk_bar = 3;
				} elseif($risk < 75) {
					$advice = "Your current risk analysis score indicates a high SEO penalty risk. You should ask us to assist with reducing your risk.";
					$risk_bar = 4;
				} elseif($risk < 100) {
					$advice = "Your current risk analysis score indicates a dangerous SEO penalty risk. You should ask us to assist with reducing your risk.";
					$risk_bar = 5;
				}

				// Weighting factors
				$domain = $json->{'scores'}->{'domain'}; //30
				$content = $json->{'scores'}->{'content'}; //25
				$link = $json->{'scores'}->{'link'}; //20
				$algorithm = $json->{'scores'}->{'algorithm'}; //25
				$social = $json->{'scores'}->{'social'}; //0 - Coming soon
				
				$domain_score = ($domain / 30) * 100;

				switch(true) {
					case ($domain_score >= 0 && $domain_score <= 10) :
						$domain_graph = $pies1;
					break;
					case ($domain_score > 10 && $domain_score <= 25) :
						$domain_graph = $pies2;
					break;
					case ($domain_score > 25 && $domain_score <= 50) :
						$domain_graph = $pies3;
					break;
					case ($domain_score > 50 && $domain_score <= 75) :
						$domain_graph = $pies4;
					break;
					case ($domain_score > 75 && $domain_score <= 100) :
						$domain_graph = $pies5;
					break;
				}

				$content_score = ($content / 25) * 100;

				switch(true) {
					case ($content_score >= 0 && $content_score <= 10) :
						$content_graph = $pies1;
					break;
					case ($content_score > 10 && $content_score <= 25) :
						$content_graph = $pies2;
					break;
					case ($content_score > 25 && $content_score <= 50) :
						$content_graph = $pies3;
					break;
					case ($content_score > 50 && $content_score <= 75) :
						$content_graph = $pies4;
					break;
					case ($content_score > 75 && $content_score <= 100) :
						$content_graph = $pies5;
					break;
				}

				$link_score = ($link / 20) * 100;

				switch(true) {
					case ($link_score >= 0 && $link_score <= 10) :
						$link_graph = $pies1;
					break;
					case ($link_score > 10 && $link_score <= 25) :
						$link_graph = $pies2;
					break;
					case ($link_score > 25 && $link_score <= 50) :
						$link_graph = $pies3;
					break;
					case ($link_score > 50 && $link_score <= 75) :
						$link_graph = $pies4;
					break;
					case ($link_score > 75 && $link_score <= 100) :
						$link_graph = $pies5;
					break;
				}

				$algorithm_score = ($algorithm / 25) * 100;

				switch(true) {
					case ($algorithm_score >= 0 && $algorithm_score <= 10) :
						$algorithm_graph = $pies1;
					break;
					case ($algorithm_score > 10 && $algorithm_score <= 25) :
						$algorithm_graph = $pies2;
					break;
					case ($algorithm_score > 25 && $algorithm_score <= 50) :
						$algorithm_graph = $pies3;
					break;
					case ($algorithm_score > 50 && $algorithm_score <= 75) :
						$algorithm_graph = $pies4;
					break;
					case ($algorithm_score > 75 && $algorithm_score <= 100) :
						$algorithm_graph = $pies5;
					break;
				}

				$social_score = ($social / 10) * 100;

				switch(true) {
					case ($social_score >= 0 && $social_score <= 10) :
						$social_graph = $pies1;
					break;
					case ($social_score > 10 && $social_score <= 25) :
						$social_graph = $pies2;
					break;
					case ($social_score > 25 && $social_score <= 50) :
						$social_graph = $pies3;
					break;
					case ($social_score > 50 && $social_score <= 75) :
						$social_graph = $pies4;
					break;
					case ($social_score > 75 && $social_score <= 100) :
						$social_graph = $pies5;
					break;
				}
				
				$calltoaction_link = $json->{'calltoaction_link'};
				$calltoaction_text = $json->{'calltoaction_text'};
			break;
		}
	} else {
		$status_description = 'Error, SEO Defend API service currently unavailable.';
	}
	
	?>
	
	<style>
		div.score_element {
			margin-bottom:4px;
			text-align:center;
			width:100%;
			border-width:1px;
			border-style: solid;
			border-top-color:#ccc;
			border-bottom-color:#bbb;
			border-left-color:#ccc;
			border-right-color:#ccc;
			background: #ffffff;
			display: block;
			margin-right:5px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			padding-top:5px;
			padding-bottom: 5px;
			cursor:help;
		}
		
		div.score_element span {
			line-height:16pt;
			text-align:center;
			font-size:8pt;
			padding-bottom: 8px;
		}
		
		div.score_element span.overall_normal {
			font-size:12pt;
			color:#8F8F8F;
		}
		
		div.score_element span.normal {
			font-size:12pt;
			color:#8F8F8F;
		}
		
		p.providedby {
			font-size:8pt;
			text-align:right;
		}
		
		div.score_element span.subitem {
			font-size:12pt;
		}
		
		div.score_element span.subitem_small {
			font-size:9pt;
		}
		
		div.score_element span.overall_subitem {
			font-size:14pt;
		}
		
		div.score_element span.overall_subitem_small {
			font-size:11pt;
		}

		div.score_element span.factor_graph img {
			width:100%;
		}
		
		span.score_advice {
			text-decoration:underline;
		}
		
		div#calltoaction {
			width:100%;
			padding:10px 10px 0px 0px;
		}
		
	</style>
	
	<p><strong>Current status:</strong> <?php echo $status_description; ?></p>
	
	<table style="border-collapse: collapse; padding: 0; width:100%; margin-top:5px;">
		<tr>
			<td colspan="9">
				<a title="<?php if ($json->{'status'} == 7) { echo $advice; } ?>"><div class="score_element">
					<span class="overall_normal">Risk Analysis Score</span>
					<br />
					<span class="overall_subitem"><?php echo $risk; ?></span>
					<span class="overall_subitem_small"> / 100
					<?php if($scores === true) { ?>
					<br />
					<span class="overall_graph"><img src="<?php echo plugins_url('img/bars/lightpurple/' . $risk_bar . '.png', __FILE__ )?>" alt="Risk Analysis Score" /></span>
					<?php } ?>
				</div></a>
			</td>
		</tr>
		<tr style="border-bottom:1px solid #BBB;">
			<td>
				<a title="Domain related SEO risk factors: Monitor domain age, authority, WHOIS information and transfer lock status."><div class="score_element">
					<span class="normal">DMN</span>
					<br />
					<span class="subitem"><?php echo $domain; ?></span>
					<span class="subitem_small"> / 30</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $domain_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
			<td>
				&nbsp;
			</td>
			<td>
				<a title="Content related SEO risk factors: Prevent content theft, duplication penalties and stop hot-linking."><div class="score_element">
					<span class="normal">CNT</span>
					<br />
					<span class="subitem"><?php echo $content; ?></span>
					<span class="subitem_small"> / 25</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $content_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
			<td>
				&nbsp;
			</td>
			<td>
				<a title="Link related SEO risk factors: Check backlink quality and velocity. Choose to link detox if required."><div class="score_element">
					<span class="normal">BKL</span>
					<br />
					<span class="subitem"><?php echo $link; ?></span>
					<span class="subitem_small"> / 20</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $link_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
			<td>
				&nbsp;
			</td>
			<td>
				<a title="Algorithm related SEO risk factors: Stay safe and avoid search engine penalties before algorithm updates strike."><div class="score_element">
					<span class="normal">ALG</span>
					<br />
					<span class="subitem"><?php echo $algorithm; ?></span>
					<span class="subitem_small"> / 25</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $algorithm_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
			<td>
				&nbsp;
			</td>
			<td>
				<a title="Social related SEO risk factors: Keep an eye on social channels, ensure spam-free profiles and avoid impersonation."><div class="score_element">
					<span class="normal">SCM</span>
					<br />
					<span class="subitem"><?php echo $social; ?></span>
					<span class="subitem_small"> / 0</span>
					<?php if($scores === true) { ?>
					<br />
					<span class="factor_graph"><?php echo $social_graph; ?></span>
					<?php } ?>
				</div></a>
			</td>
		</tr>
		<tr>
			<td colspan="9">
				<div id="calltoaction">
					<form action="<?php echo ($calltoaction_link ? $calltoaction_link : 'https://seodefend.com'); ?>" method="post" target="_blank">
						<input type="submit" class="button" value="<?php echo ($calltoaction_text ? $calltoaction_text : 'Learn more about SEO Defend, while you wait...'); ?>" />
					</form>
				</div>
			</td>
		</tr>
	</table>
	
	<p class="providedby">
		Powered by <a href="https://seodefend.com" target="_blank" title="SEO Defend">SEO Defend</a>.
	</p>
	<?php
} 

// Create the function use in the action hook

function seodefend_add_dashboard_widgets() {
	wp_add_dashboard_widget('seodefend_dashboard_widget',  '<img src="' . plugins_url( 'img/small_icon.gif', __FILE__ ) . '" alt="SEO Defend" /> SEO Defend: SEO Protection Dashboard', 'seodefend_dashboard_widget_function');
	
	// Globalize the metaboxes array, this holds all the widgets for wp-admin

	global $wp_meta_boxes;
	
	// Get the regular dashboard widgets array 
	// (which has our new widget already but at the end)

	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
	// Backup and delete our new dashbaord widget from the end of the array

	$example_widget_backup = array('seodefend_dashboard_widget' => $normal_dashboard['seodefend_dashboard_widget']);
	unset($normal_dashboard['seodefend_dashboard_widget']);

	// Merge the two arrays together so our widget is at the beginning

	$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

	// Save the sorted array back into the original metaboxes 

	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
} 

// Hook into the 'wp_dashboard_setup' action to register our other functions

add_action('wp_dashboard_setup', 'seodefend_add_dashboard_widgets' ); // Hint: For Multisite Network Admin Dashboard use wp_network_dashboard_setup instead of wp_dashboard_setup.

?>