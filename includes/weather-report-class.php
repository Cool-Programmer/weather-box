<?php
	class Weather_Report_Widget extends WP_Widget
	{
		function __construct()
		{
			parent::__construct(
				'weather_report',
				__('Weather Report', 'wr-domain'),
				[
					'description' => __('Show live weather updates on your WordPress website', 'wr-domain')
				]
			);
		}



		public function widget($args, $instance)
		{
			$city = $instance['city'];
			$state = $instance['state'];

			$options = [
				'use_geolocation' => $instance['use_geolocation'] ? true : false,
				'show_humidity' => $instance['show_humidity'] ? true : false,
				'temp_type' => $instance['temp_type']
			];

			echo $args['before_widget'];
				echo $this->getWeather($city, $state, $options);
			echo $args['after_widget'];
		}


		private function getWeather($city, $state, $options)
		{
			// Geoplugin init
			$geoplugin = new geoPlugin();
			$geoplugin->locate();

			if ($options['use_geolocation']) {
				$city = $geoplugin->city;
				$state = $geoplugin->region;
			}

			$json_string = file_get_contents("http://api.wunderground.com/api/6a71941176e55332/geolookup/conditions/q/$state/$city.json");
			$parsed_json = json_decode($json_string);
			$location = $parsed_json->{'location'}->{'city'} . ', ' . $parsed_json->{'location'}->{'state'};
			$weather = $parsed_json->{'current_observation'}->{'weather'};
			$icon_url = $parsed_json->{'current_observation'}->{'icon_url'};
			$temp_faren = $parsed_json->{'current_observation'}->{'temp_f'};
			$temp_celsi = $parsed_json->{'current_observation'}->{'temp_c'};
			$relative_humidity = $parsed_json->{'current_observation'}->{'relative_humidity'};

			?>
				<div class="city-weather">
					<h3><?php echo $location; ?></h3>
					<?php if($options['temp_type'] == 'Celsius'): ?>
						<h1><?php echo $temp_c ?> °C</h1>
					<?php elseif($options['temp_type'] == 'Farenheit'): ?>
						<h1><?php echo $temp_f ?> °F</h1>
					<?php else: ?>
						<h1><?php echo $temp_f ?> °F <?php echo $temp_c ?> °C</h1>
					<?php endif; ?>
					<?php echo $weather; ?>
					<img src="<?php echo $icon_url ?>" alt="">
					<?php if($options['show_humidity']): ?>
						<div>
							<strong>Humidity: <?php echo $relative_humidity?></strong>
						</div>
					<?php endif; ?>
				</div>
			<?php
		}


		public function form($instance)
		{
			$city = $instance['city'];
			$state = $instance['state'];
			$use_geolocation = $instance['use_geolocation'];
			$show_humidity = $instance['show_humidity'];
			$temp_type = $instance['temp_type'];
			?>
				<p>
					<label for="<?php echo $this->get_field_id('use_geolocation') ?>"><?php _e('Use geolocation', 'wr-domain'); ?></label>
					<input type="checkbox" <?php checked($instance['use_geolocation'], 'on') ?> class="checkbox" name="<?php echo $this->get_field_name('use_geolocation') ?>" id="<?php echo $this->get_field_id('use_geolocation'); ?>">
				</p>

				<p>
					<label for="<?php echo $this->get_field_id('show_humidity') ?>"><?php _e('Show Humidity', 'wr-domain'); ?></label>
					<input type="checkbox" <?php checked($instance['show_humidity'], 'on') ?> class="checkbox" name="<?php echo $this->get_field_name('show_humidity') ?>" id="<?php echo $this->get_field_id('show_humidity'); ?>">
				</p>

				<p>
					<label for="<?php echo $this->get_field_id('city') ?>"><?php _e('City', 'wr-domain'); ?></label>
					<input type="text" class="widefat" name="<?php echo $this->get_field_name('city') ?>" id="<?php echo $this->get_field_id('city'); ?>" value="<?php echo esc_attr($city); ?>"></input>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id('state') ?>"><?php _e('State', 'wr-domain'); ?></label>
					<input type="text" class="widefat" name="<?php echo $this->get_field_name('state') ?>" id="<?php echo $this->get_field_id('state'); ?>" value="<?php echo esc_attr($state); ?>"></input>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id('temp_type') ?>"><?php _e('Temperature Type', 'wr-domain'); ?></label>
					<select class="widefat" name="<?php echo $this->get_field_name('temp_type') ?>" id="<?php echo $this->get_field_id('temp_type'); ?>">
						<option value="Farenheit" <?php echo ($temp_type=="Farenheit") ? "selected" : "" ?>>Farenheit</option>
						<option value="Celsius" <?php echo ($temp_type=="Celsius") ? "selected" : "" ?>>Celsius</option>
						<option value="Both" <?php echo ($temp_type=="Both") ? "selected" : "" ?>>Both</option>
					</select>
				</p>
			<?php
		}


		public function update($new_instance, $old_instance)
		{
			$instance = [
				'title' => (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '',
				'city' => (!empty($new_instance['city'])) ? strip_tags($new_instance['city']) : '',
				'state' => (!empty($new_instance['state'])) ? strip_tags($new_instance['state']) : '',
				'show_humidity' => (!empty($new_instance['show_humidity'])) ? strip_tags($new_instance['show_humidity']) : '',
				'use_geolocation' => (!empty($new_instance['use_geolocation'])) ? strip_tags($new_instance['use_geolocation']) : '',
				'temp_type' => (!empty($new_instance['temp_type'])) ? strip_tags($new_instance['temp_type']) : ''
			];

			return $instance;
		}
	}
