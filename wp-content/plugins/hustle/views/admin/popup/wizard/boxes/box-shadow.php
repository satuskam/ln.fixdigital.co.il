<div id="wph-wizard-design-shadow" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Drop Shadow", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

	</div>

	<div class="wpmudev-box-right">

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-popup-shadow" class="toggle-checkbox" type="checkbox" data-attribute="drop_shadow" {{_.checked(_.isTrue(drop_shadow), true)}}>

				<label class="wpmudev-switch-design" for="wph-popup-shadow" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-popup-shadow"><?php esc_attr_e( "Show drop shadow", Opt_In::TEXT_DOMAIN ); ?></label>

		</div>

		<div id="wph-wizard-design-shadow-options" class="wpmudev-box-gray {{ ( _.isTrue(drop_shadow) ) ? 'wpmudev-show' : 'wpmudev-hidden' }}">

			<div class="wpmudev-row">

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "X-offset", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{drop_shadow_x}}" data-attribute="drop_shadow_x" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Y-offset", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{drop_shadow_y}}" data-attribute="drop_shadow_y" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Blur", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{drop_shadow_blur}}" data-attribute="drop_shadow_blur" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Spread", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{drop_shadow_spread}}" data-attribute="drop_shadow_spread" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Color", Opt_In::TEXT_DOMAIN ); ?></label>

					<div class="wpmudev-picker"><input id="popup_modal_shadow" class="wpmudev-color_picker" type="text"  value="{{drop_shadow_color}}" data-attribute="drop_shadow_color" data-alpha="true" /></div>

				</div>

			</div>

		</div>

	</div>

</div><?php // #wph-wizard-design-shadow ?>