<div id="app">
    <div class="col-lg-8 col-lg-offset-2">
        <a href="<?php echo home_url(); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/dist/images/site-header.png" alt="جشنواره ایران خودرو" class="img-responsive logo-img">
        </a>
    </div>
    <div class="clearfix"></div>
</div>

<div class="app-container" style="background-color: #fff !important;width: 100%;min-height: 50px;margin-top: -18px;padding: 25px;">

    <ul class="home-menu" data-home-section="menu">
        <li class="col-md-4 text-center">
            <i class="fa fa-camera-retro"></i>
            <a href="#" data-run="festival"> شرکت در جشنواره<span>Participation in Festival</span></a>
        </li>
        <li class="col-md-4 text-center">
            <i class="fa fa-newspaper-o"></i>
            <a href="#" data-run="news"> اخبار و رویداد ها<span>News and events</span></a>
        </li>
        <li class="col-md-4 text-center">
            <i class="fa fa-envelope-open-o"></i>
            <a href="#" data-run="contact"> ارتباط و پشتیبانی<span>Contact us</span></a>
        </li>
    </ul>

    <div id="contact" data-home-section="contact">
        <div class="contact-text">
			<?php
			echo apply_filters( 'the_content', get_the_content( null, false, 2 ) );
			?>
        </div>
        <a href="#" data-run="back_to_menu" class="btn btn-warning back-btn">بازگشت</a>
    </div>

    <div id="news" data-home-section="news">
		<?php
		$list = '';
		$news = '';

		//Prepare News List
		$args = array(
			'posts_per_page' => 5,
			'order'          => 'DESC',
			'post_type'      => 'post'
		);

		// The Query
		$query = new WP_Query( $args );
		$count = $query->post_count;
		$i     = 1;
		while ( $query->have_posts() ):
			$query->the_post();

			//push to list
			$list .= '
             <div class="home-box p-box">
                <h2>
                    <a href="#" class="text-danger" data-run="show_news" data-news-id="' . get_the_ID() . '">' . get_the_title() . '</a>
                </h2>
                <time>تاریخ انتشار : ' . get_the_date( "Y/m/d ساعت H:i" ) . '</time>
            </div>
            ';

			// Push to News
			$news .= '
                <div class="section-post" data-home-section="post-' . get_the_ID() . '">
                    <div class="home-box">
                        <span class="post-title text-danger">' . get_the_title() . '</span>
                        <time>تاریخ انتشار : ' . get_the_date( "Y/m/d ساعت H:i" ) . '</time>
                        <div>' . apply_filters( 'the_content', get_the_content() ) . '</div>
                    </div>
                    <a href="#" data-run="back_to_post" class="btn btn-warning back-btn">بازگشت</a>
                 </div>
            ';

		endwhile;
		wp_reset_postdata();
		?>
		<?php echo $list; ?>
        <br>
        <a href="#" data-run="back_to_menu" class="btn btn-warning back-btn">بازگشت</a>
    </div>

    <!-- List Of Post -->
	<?php echo $news; ?>

    <!-- Festival Form -->
    <div class="festival-form" data-home-section="festival">
        <div class="contact-text" style="padding: 25px;">

            <div data-step-form="1">
                <h2 style="font-size: 25px;font-weight: bold;margin-bottom: 25px;margin-top: 10px;"><?php echo get_the_title( 10 ); ?></h2>
				<?php
				echo apply_filters( 'the_content', get_the_content( null, false, 10 ) );
				?>

                <br>
                <div class="checkbox">
                    <input type="checkbox" id="low">
                    <label for="low">
                        <span>
                            قوانین شرکت در مسابقه را مطالعه کردم
                        </span>
                    </label>
                </div>
                <br>
                <button class="btn btn-success" data-run="low" style="width: 40% !important;
    margin-right: auto !important;
    margin-left: auto !important;
    display: block;height: 45px;margin-top: -20px;">مرحله بعدی</button>
                <br>
            </div>

            <div data-step-form="2" style="display: none;">

                <form action="" method="post" id="add_new" enctype="multipart/form-data" autocomplete="off">

                    <!-- Right Form -->
                    <div class="col-sm-6">
                        <div class="title-line" style="color:#000;"><i class="fa fa-user"></i>&nbsp; مشخصات فردی
                        </div>
                        <div class="group-input">

                            <div class="form-group form-inline">
                                <label> نام <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="" placeholder="لطفا نام خود را وارد نمایید" class="form-control rtl input-group" autocomplete="off">
                            </div>

                            <div class="form-group form-inline">
                                <label> نام خانوادگی <span class="text-danger">*</span></label>
                                <input type="text" name="family" value="" placeholder="لطفا نام خانوادگی خود را وارد نمایید" class="form-control rtl input-group" autocomplete="off">
                            </div>

                            <div class="form-group form-inline">
                                <label> تاریخ تولد <span class="text-danger">*</span></label>
                                <input type="text" name="birthday" class="form-control ltr input-group" id="datapicker-persian" placeholder="xxxx/xx/xx" data-tooltip="hover-tooltip" autocomplete="off">
                            </div>

                            <div class="form-group form-inline">
                                <label> استان محل اقامت <span class="text-danger">*</span></label>
                                <select name="province" class="form-control rtl input-group" autocomplete="off" data-change="set_city_list">
									<?php
									global $wpdb;
									$IR_CITY_LIST = get_option( 'IRAN_CITY_LIST' );
									$Iran_city    = ( $IR_CITY_LIST !== false ? $IR_CITY_LIST : array() );
									$province     = $wpdb->get_results( "SELECT * FROM `province`" );
									foreach ( $province as $item ) {

										// Get List Of City
										if ( $IR_CITY_LIST === false ) {
											$city = $wpdb->get_results( "SELECT * FROM `city` WHERE `province` = " . $item->id );
											foreach ( $city as $c ) {
												$Iran_city[ $item->id ][ $c->id ] = $c->name;
											}
										}

										?>
                                        <option value="<?php echo $item->id; ?>"><?php echo $item->name; ?></option>
										<?php
									}
									?>
                                </select>
                            </div>

							<?php
							// Save in Option
							if ( $IR_CITY_LIST === false ) {
								update_option( 'IRAN_CITY_LIST', $Iran_city, 'no' );
							}
							?>

                            <div class="form-group form-inline">
                                <label> شهر <span class="text-danger">*</span></label>
                                <script>
                                    var Iran_City_List = '<?php echo json_encode( $Iran_city, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK ); ?>';
                                </script>
                                <select name="city" class="form-control rtl input-group" autocomplete="off">
                                    <!-- Do Js -->
                                </select>
                            </div>

                            <div class="form-group form-inline">
                                <label> آدرس کامل <span class="text-danger">*</span></label>
                                <textarea type="text" style="min-height: 100px;" name="address" class="form-control rtl input-group" autocomplete="off"></textarea>
                            </div>

                            <div class="form-group form-inline">
                                <label> شماره همراه <span class="text-danger">*</span></label>
                                <input maxlength="11" type="text" name="mobile" data-put="number" class="form-control ltr input-group" autocomplete="off" placeholder="09xxxxxxxxx">
                            </div>

                            <div class="form-group form-inline">
                                <label> محل عکس <span class="text-danger">*</span></label>
                                <input type="text" name="picture_place" value="" placeholder="محل عکس را وارد نمایید" class="form-control rtl input-group" autocomplete="off">
                            </div>

                        </div>

                        <div class="title-line" style="color:#000;">
                            <i class="fa fa-instagram"></i>&nbsp; صفحه اینستاگرام
                        </div>
                        <div class="group-input">
                            <div class="form-group form-inline">
                                <label> آی دی اینستاگرام <span class="text-danger">*</span></label>
                                <input type="text" name="instagram" value="" placeholder="@username" class="form-control ltr input-group" autocomplete="off">
                            </div>
                            <p class="desc-input text-justify" style="color: #929292;">
                                <i class="fa fa-angle-left"></i>
                                شناسه صفحه اینستاگرام همواره با علامت @ آغاز می شود به عنوان مثال
                                ikco.festival@
                                <br/>
                                <i class="fa fa-angle-left"></i>
                                در صورتی که آی دی اینستاگرام شما
                                ikco.festival@
                                باشد.این صفحه باید به آدرس اینترنتی
                                <a href="https://www.instagram.com/ikco.festival/" target="_blank">https://www.instagram.com/ikco.festival</a>
                                در دسترس باشد.
                                <br/>
                                <i class="fa fa-angle-left"></i>
                                در تمام مراحل جشنواره صفحه اینستاگرام شما می بایست به حالت عمومی
                                (Public)
                                باشد. بدیهی است کاربرانی که صفحه اینستاگرام آنان به صورت Private یا مخفی می باشد و برای نمایش عکس نیاز به Follow کردن دارند در مسابقه شرکت داده نخواهند شد.
                            </p>
                        </div>

                    </div>

                    <div class="col-sm-6">

                        <div class="title-line" style="color:#000;"><i class="fa fa-camera"></i>&nbsp; تصاویر</div>
                        <div class="group-input">

                            <p class="desc-input text-justify" style="color: #818181;">
                                <i class="fa fa-angle-left"></i>
                                هر کابر میتواند حداقل یک عکس و حداکثر 5 عکس را ارسال کند.
                                <br/>
                                <i class="fa fa-angle-left"></i>
                                عکس ها می بایست به فرمت jpg و حداکثر حجم برای هر عکس
                                3 مگابایت می باشد.
                                <br/>
                                <i class="fa fa-angle-left"></i>
                                برای دریافت آدرس هر عکس در اینستاگرام ، ابتدا توسط مرورگر وارد صفحه اینستاگرام خود شوید. سپس با کلیک بر روی عکس مورد نظر ، آدرس آن در نوار آدرس بار مرورگر ظاهر می شود.
                                آدرس هر عکس عموما به صورت
                                <span class="text-danger">
                                    https://www.instagram.com/p/xxxxxx
                                </span>
                                هست. </p>


                            <br/>
                            <div class="panel-group" id="accordion_file_list" role="tablist" aria-multiselectable="true">
								<?php for ( $i = 1; $i <= 5; $i ++ ) { ?>
                                    <div class="panel panel-default" style="margin-bottom: 15px;">
                                        <div class="panel-heading" data-toggle="collapse" data-parent="#accordion_file_list" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>" id="heading<?php echo $i; ?>" style="cursor: pointer;">
                                            <h4 class="panel-title" style=" padding: 8px; padding-right:0px;">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion_file_list" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                                                    عکس شماره <?php echo number_format_i18n( $i ); ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?php echo $i; ?>" class="panel-collapse collapse<?php if ( $i == 1 ) { ?> in<?php } ?>" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>">
                                            <div class="panel-body">
                                                <div class="form-group form-inline">
                                                    <label> فایل عکس
														<?php if ( $i == 1 ) { ?>
                                                            <span class="text-danger">*</span>
														<?php } ?>
                                                    </label>
                                                    <input type="file" id="file_<?php echo $i; ?>" name="file_<?php echo $i; ?>" class="form-control ltr input-group" data-file="input-file">
                                                </div>

                                                <div class="form-group form-inline">
                                                    <label> آدرس عکس
														<?php if ( $i == 1 ) { ?>
                                                            <span class="text-danger">*</span>
														<?php } ?>
                                                    </label>
                                                    <input type="text" name="link_<?php echo $i; ?>" value="" placeholder="https://www.instagram.com/p/xxxxxx" class="form-control ltr input-group" autocomplete="off">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
									<?php
								}
								?>

                            </div> <!-- collapse -->

                        </div>

                        <div class="text-center">
                            <br>
                            <img src="<?php echo get_template_directory_uri(); ?>/php/captcha/captcha.php?bg=eb4c3f&tcolor=fff" class="img-captcha" alt="captcha" style="display: inline-block; border-radius:5px;">
                            <input type="text" name="captcha" value="" class="form-control rtl input-group" placeholder="کد امنیتی را وارد کنید" style="min-width: 40px !important;display: inline-block;margin-right: 10px;margin-left: 10px;height: 38px; margin-top: 3px;width: 180px;">
                            <input type="submit" class="btn btn-primary" value="ثبت نام" style="border: 0px;padding: 10px;min-width: 80px;">
                        </div>

                    </div>
                    <div class="clearfix"></div>

                </form>

                <div id="error-massage" class="panel" style=" margin-top: 25px;color: #fff;background-color: #565353;padding: 10px; display:none;"></div>

            </div>
        </div>
    </div>

</div>


