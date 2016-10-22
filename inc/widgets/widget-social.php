<?php

/**
 * Social  Widget
 * Slimpy Theme
 */
class slimpy_social_widget extends WP_Widget
{
	 function __construct(){
            
            $widget_ops = array('classname' => 'slimpy-social','description' => esc_html__( "Slimpy Social Widget" ,'slimpy') );
            parent::__construct('slimpy-social', esc_html__('Slimpy Social Widget','slimpy'), $widget_ops);
            
    }

    function widget($args , $instance) {
    	extract($args);
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Follow us' , 'slimpy');

        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        /**
         * Widget Content
         */ ?>

        <!-- social icons -->
        <div class="social-icons sticky-sidebar-social">

            <?php slimpy_social_icons(); ?>

        </div><!-- end social icons --><?php

        echo $after_widget;
    }

    function form($instance) {
      if(!isset($instance['title'])) $instance['title'] = esc_html__('Follow us' , 'slimpy'); ?>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title ','slimpy') ?></label>

      <input type="text" value="<?php echo esc_attr($instance['title']); ?>"
                          name="<?php echo $this->get_field_name('title'); ?>"
                          id="<?php $this->get_field_id('title'); ?>"
                          class="widefat" />
      </p><?php
    }

}
?>