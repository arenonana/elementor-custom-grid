<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor product grid widget
 *
 * Elementor widget that create a grid by restAPI
 *
 * @since 1.0.0
 */
class Elementor_oEmbed_Widget extends \Elementor\Widget_Base
{

	/**
	 * Get widget name.
	 *
	 * Retrieve ProductGrid widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'ProductGrid';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve ProductGrid widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('Product Grid', 'elementor-ProductGrid-widget');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve ProductGrid widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-code';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url()
	{
		return 'https://developers.elementor.com/docs/widgets/';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the ProductGrid widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return ['basic'];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the ProductGrid widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords()
	{
		return ['ProductGrid', 'gtid', 'products'];
	}

	/**
	 * Register ProductGrid widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Settings', 'elementor-ProductGrid-widget'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'numbers',
			[
				'label' => esc_html__('Number of Products', 'elementor-ProductGrid-widget'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'placeholder' => 3,
				'default' => '3'

			]
		);

		$this->end_controls_section();
	}

	function sgh_get_posts()
	{
		$response = wp_remote_get('https://dummyjson.com/products');

		if (is_array($response) && !is_wp_error($response)) {
			return json_decode($response['body']); // use the content
		} else
			return false;
	}
	/**
	 * Render ProductGrid widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$i = 0;
		$plugin_url = plugin_dir_url(__FILE__);
		wp_enqueue_style('style',  $plugin_url . "/assets/elementor-custom-grid-style.css");
		wp_enqueue_script('script', $plugin_url . "assets/script.js", array('jquery'));
		wp_enqueue_style('load-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css', false);
		wp_enqueue_style('bootstrap', "https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css");
?>

		<div class="container d-flex justify-content-center mt-50 mb-50">

			<div class="custom-modal hide">
				<div class="custom-modal-content">
					<img src="" alt="" class="modat-img">
				</div>
				<span class="close-btn"></span>
			</div>

			<div class="row">
				<?php

				if (!$result = $this->sgh_get_posts()) {
					echo '<h1>an issue with API</h1>';
					exit;
				} else {
					foreach ($result->products as $data) :
						$i++;
						if ($i > $settings['numbers'])
							break;
				?>

						<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="card-img-actions">
										<span class="brand"><?= esc_html($data->brand)  ?></span>
										<?= $data->discountPercentage > 0 ? '<span class="discount">-' . esc_html(ceil($data->discountPercentage)) . '%</span>' : ''; ?>
										<div class="card-img img-fluid" style="background-image: url(<?= esc_html($data->thumbnail) ?>);" width="100%" height="350" alt=""></div>
									</div>
									<div class="gallery">
										<?php
										// foreach throgh gallery
										foreach ($data->images as $img) {
											echo '<img class="gallery-img" src="' . $img . '" alt="" />';
										}
										?>

									</div>
								</div>

								<div class="card-body bg-light text-center">
									<div class="mb-2">
										<h6 class="font-weight-semibold mb-2">
											<a href="#" class="text-default mb-2 title" data-abc="true"><?= esc_html($data->title) ?></a>
										</h6>

										<a href="#" class="text-muted category secondary" data-abc="true"><?= esc_html($data->category) ?></a>
									</div>
									<h3 class="mb-0 font-weight-semibold"><?= $data->discountPercentage > 0 ? '<del class="secondary">$' . esc_html($data->price) . '</del> '  . '<span class="primary">$' . esc_html(ceil($data->price - ($data->price * ($data->discountPercentage / 100)))) . '<span>' : '$<span class="primary">' . esc_html($data->price) . '<span>'; ?></h3>


									<div>
										<?php
										for ($j = 0; $j < 5; $j++) {
											if ($j < floor($data->rating)) {
												echo '<i class="fa fa-star star"></i>';
											} else {
												echo '<i class="fa fa-star star secondary"></i>';
											}
										}
										?>
									</div>

									<div class="text-muted mb-3 description"><?= esc_html($data->description) ?></div>

									<button type="button" class="btn bg-cart">
										<i class="fa fa-cart-plus mr-2">
											<span class="quantity"><?= esc_html($data->stock) ?></span>
										</i>
										<?= esc_html__('Add to cart', 'elementor-ProductGrid-widget'); ?>
									</button>
								</div>
							</div>
						</div>
					<?php
					endforeach; ?>
				<?php
				}
				?>





			</div>
		</div>
<?php
	}
}
