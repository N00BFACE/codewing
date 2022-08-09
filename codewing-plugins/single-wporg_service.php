<?php
add_action( 'wp_enqueue_scripts', 'post_style' );
function post_style() {
    wp_register_style('new_style', plugins_url( 'plugins-codewing/assets/css/style.css' ));
    wp_enqueue_style( 'new_style');
}
add_action( 'the_content', 'display_meta_data');

function display_meta_data() {
    $id = get_the_ID();
    ?>
    <!-- Creating Banner Layout in HTML -->
    <section class="banner" id="home">
        <div class="textBx">
            <h2>Hello, I'm<br> 
            <span>
                <?php echo esc_attr($wporg_name = get_post_meta( $id, 'portfolio_name', true )); ?>
            </span></h2>
            <h3>I specialize on
                <?php echo esc_attr($wporg_wdyso = get_post_meta( $id, 'portfolio_wdyso', true )); ?>
            </h3>
        </div>
    </section>

    <!-- Creating About Me Layout in HTML -->
    <section class="about" id="about">
        <div class="heading">
            <p>Get to Know me</p>
            <h2>About Me</h2>
        </div>
        <div class="content">
            <div class="w50">
                <img src="images/stick-figure.png" alt="" class="proPic">
            </div>
            <div class="contentBx w50">
                <h3>Who am i?</h3>
                <h2>I'm 
                    <b>
                        <?php echo esc_attr($wporg_name = get_post_meta( $id, 'portfolio_name', true )); ?>
                    </b></h2>
                <p>
                    <?php echo esc_attr($wporg_yourself = get_post_meta( $id, 'portfolio_yourself', true )); ?>
                </p><br>
                <p>Example:</p>
            </div>
        </div>
    </section>

    <!-- Creating Services Layout in HTML -->
    <section class="projects" id="projects">
        <div class="heading white">
            <h2>My Projects</h2>
            <p>Look at what I made</p>
        </div>
        <div class="content">
            <div class="projectsBx">
                <i class="fa fa-css3 fa-4x"></i>
                <h3>
                    <?php echo esc_attr( $wporg_first_project = get_post_meta( $id, 'portfolio_first_project', true ) ); ?>
                </h3> 
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            </div>
            <div class="projectsBx">
                <i class="fa fa-wordpress fa-4x"></i>
                <h3>
                    <?php echo esc_attr( $wporg_second_project = get_post_meta( $id, 'portfolio_second_project', true ) ); ?>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            </div>
            <div class="projectsBx">
                <i class="fa fa-drupal fa-4x"></i>
                <h3>
                    <?php echo esc_attr( $wporg_third_project = get_post_meta( $id, 'portfolio_third_project', true ) ); ?>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            </div>
            <div class="projectsBx">
                <i class="fa fa-joomla fa-4x"></i>
                <h3>
                    <?php echo esc_attr( $wporg_fourth_project = get_post_meta( $id, 'portfolio_fourth_project', true ) ); ?>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            </div>
        </div>
    </section>
  
    <!-- Creating Services Layout in HTML -->
    <hr>
    <section class="projects" id="projects">
        <div class="heading white">
            <h2>Services I Provide</h2>
            <p>Look at what I can do</p>
        </div>
        <div class="content">
            <div class="projectsBx">
                <i class="fa fa-css3 fa-4x"></i>
                <h3>
                    <?php echo esc_attr( $wporg_first_service = get_post_meta( $id, 'portfolio_first_service', true ) ); ?>
                </h3> 
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            </div>
            <div class="projectsBx">
                <i class="fa fa-wordpress fa-4x"></i>
                <h3>
                    <?php echo esc_attr( $wporg_second_service = get_post_meta( $id, 'portfolio_second_service', true ) ); ?>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            </div>
            <div class="projectsBx">
                <i class="fa fa-drupal fa-4x"></i>
                <h3>
                    <?php echo esc_attr( $wporg_third_service = get_post_meta( $id, 'portfolio_third_service', true ) ); ?>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            </div>
            <div class="projectsBx">
                <i class="fa fa-joomla fa-4x"></i>
                <h3>
                    <?php echo esc_attr( $wporg_fourth_service = get_post_meta( $id, 'portfolio_fourth_service', true ) ); ?>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
            </div>
        </div>
    </section>
<?php }