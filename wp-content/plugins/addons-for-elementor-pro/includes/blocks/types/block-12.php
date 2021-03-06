<?php

namespace LivemeshAddons\Blocks;

class LAE_Block_12 extends LAE_Block {

    function inner($posts, $settings) {

        $output = '';

        $post_count = 1;

        $num_of_columns = $settings['per_line1'];

        $block_layout = new LAE_Block_Layout();

        $column_class = $this->get_column_class(intval($num_of_columns));

        if (!empty($posts)) {

            foreach ($posts as $post) {

                if ($num_of_columns == 1) {

                    $output .= $block_layout->open_column($column_class);

                    // big posts for posts
                    if ($post_count <= 1) {

                        $module2 = new \LivemeshAddons\Modules\LAE_Module_9($post, $settings);

                        $output .= $module2->render();

                    }
                    else {

                        $module6 = new \LivemeshAddons\Modules\LAE_Module_3($post, $settings);

                        $output .= $module6->render();
                    }

                    $post_count++;

                }
                else {

                    if ($post_count == 1 || ($post_count % $num_of_columns == 1))
                        $output .= $block_layout->open_row();

                    $output .= $block_layout->open_column($column_class);

                    // big posts at the top of each column
                    if ($post_count <= $num_of_columns) {

                        $module2 = new \LivemeshAddons\Modules\LAE_Module_9($post, $settings);

                        $output .= $module2->render();

                    }
                    else {

                        $module6 = new \LivemeshAddons\Modules\LAE_Module_3($post, $settings);

                        $output .= $module6->render();
                    }

                    $output .= $block_layout->close_column($column_class);

                    if ($post_count % $num_of_columns == 0)
                        $output .= $block_layout->close_row();

                    $post_count++;
                }

            };

            $output .= $block_layout->close_all_tags();

        };

        return $output;

    }

    function get_block_class() {

        return 'lae-block-posts lae-block-12';

    }

    function get_grid_classes($settings) {

        $grid_classes = ' lae-grid-desktop-' . $settings['per_line1'];

        $grid_classes .= ' lae-grid-tablet-2';

        $grid_classes .= ' lae-grid-mobile-1';

        return $grid_classes;

    }
}