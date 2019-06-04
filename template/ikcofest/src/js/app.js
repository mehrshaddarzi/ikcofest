/* Persian Number */
/* Use $('span[id]').html(jQuery.persianNumbers(data.number)); */
(function (a) {
    a.extend({
        persianNumbers: function (b) {
            var g = {0: "۰", 1: "۱", 2: "۲", 3: "۳", 4: "۴", 5: "۵", 6: "۶", 7: "۷", 8: "۸", 9: "۹"};
            var d = (b + "").split("");
            var f = d.length;
            var c;
            for (var e = 0; e <= f; e++) {
                c = d[e];
                if (g[c]) {
                    d[e] = g[c]
                }
            }
            return d.join("")
        }
    })
})(jQuery);

/* Start Application */
jQuery(document).ready(function ($) {

    var app = {
        set_city_list: function (elm) {
            let province = elm.val();
            let city_list = JSON.parse(Iran_City_List);
            let options = '';
            Object.keys(city_list[province]).forEach((key) => {
                options += '<option value="' + key + '">' + city_list[province][key] + '</option>';
            });
            jQuery('select[name=city]').html(options);
        },
        low: function (elm) {
            if (jQuery("input[id=low]").is(":checked")) {
                jQuery("[data-step-form=1]").fadeOut('normal');
                setTimeout(function () {
                    jQuery("[data-step-form=2]").fadeIn('normal');
                }, 100);
            } else {
                alert("لطفا قوانین را مطالعه کنید");
            }
        },
        festival: function () {
            this.show('[data-home-section="festival"]');
        },
        back_to_post: function (elm) {
            this.news(elm);
        },
        show_news: function (elm) {
            this.show('[data-home-section="post-' + elm.attr('data-news-id') + '"]');
        },
        news: function (elm) {
            this.show('[data-home-section="news"]');
        },
        back_to_menu: function (elm) {
            this.show('ul[data-home-section="menu"]');
        },
        contact: function (elm) {
            this.show('#contact');
        },
        show: function (element) {
            jQuery("[data-home-section]").fadeOut('normal');
            setTimeout(function () {
                jQuery(element).fadeIn('normal');
            }, 800);
        },
        run: function () {

        }
    };

    app.run();
    jQuery(document).on("click", "[data-run]", function (e) {
        e.preventDefault();
        app[jQuery(this).attr("data-run")](jQuery(this));
    });
    jQuery(document).on("change", "[data-change]", function (e) {
        e.preventDefault();
        app[jQuery(this).attr("data-change")](jQuery(this));
    });

    // Check IS Date Format
    function isDate(str) {
        let parms = str.split(/[\.\-\/]/);
        let yyyy = parseInt(parms[2], 10);
        let mm = parseInt(parms[1], 10);
        let dd = parseInt(parms[0], 10);
        let date = new Date(yyyy, mm - 1, dd, 0, 0, 0, 0);
        return mm === (date.getMonth() + 1) && dd === date.getDate() && yyyy === date.getFullYear();
    }

    // Disable enter submit
    jQuery(document).on('keyup keypress', 'form input[type="text"]', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            return false;
        }
    });

    /* Numeric input */
    jQuery("input[data-put=number]").numeric(false, function () {
        alert("لطفا تنها عدد وارد نمائید.");
        this.value = "";
        this.focus();
    });

    /* Bootstrap File */
    jQuery("input[data-file=input-file]").filestyle({input: false, buttonText: "انتخاب کنید"});

    /* Bootstrap DatePicker */
    jQuery("input[id=datapicker-persian]").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: 'c-60:c+5'
    });

    /* Set Default City List */
    let province_select = jQuery('select[name=province]');
    if (province_select) {
        let province = province_select.val();
        let city_list = JSON.parse(Iran_City_List);
        let options = '';
        Object.keys(city_list[province]).forEach((key) => {
            options += '<option value="' + key + '">' + city_list[province][key] + '</option>';
        });
        jQuery('select[name=city]').html(options);
    }

    /* Form submit */
    $(document).on("submit", "form#add_new", function (e) {
        e.preventDefault();

        // Show Loading and Scroll To Bottom
        let alertBox = jQuery("div#error-massage");
        alertBox.html("لطفا کمی صبر کنید ...").show();
        jQuery("html, body").animate({scrollTop: jQuery("#error-massage").offset().top}, 800);

        // Validation Name Field
        let name = $.trim($('input[name=name]').val());
        if (name.length === 0) {
            alertBox.html("خطا : لطفا نام خود را وارد نمایید");
            return false;
        } else {
            if (!/^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأءًٌٍَُِّ\s\n\r\t\d\(\)\[\]\{\}.,،;\-؛]+$/.test(name)) {
                alertBox.html("خطا : نام تنها شامل کاراکتر فارسی باید باشد");
                return false;
            }
        }

        // User Family
        let family = $.trim($('input[name=family]').val());
        if (family.length === 0) {
            alertBox.html("خطا : لطفا نام خانوادگی خود را وارد نمایید");
            return false;
        } else {
            if (!/^[پچجحخهعغفقثصضشسیبلاتنمکگوئدذرزطظژؤإأءًٌٍَُِّ\s\n\r\t\d\(\)\[\]\{\}.,،;\-؛]+$/.test(name)) {
                alertBox.html("خطا : نام خانوادگی تنها شامل کاراکتر فارسی باید باشد");
                return false;
            }
        }

        // User Birthday
        let birthday = $.trim($('input[name=birthday]').val());
        if (birthday.length === 0) {
            alertBox.html("خطا : لطفا تاریخ تولد خود را وارد نمایید");
            return false;
        } else {
            if (!isDate(birthday)) {
                alertBox.html("خطا : فرمت تاریخ تولد اشتباه است");
                return false;
            }
        }

        // User Address
        let address = $.trim($('textarea[name=address]').val());
        if (address.length === 0) {
            alertBox.html("خطا : لطفا آدرس خود را وارد نمایید");
            return false;
        }

        // User Mobile
        //https://hitos.ir/question/4/%D8%A7%D8%B9%D8%AA%D8%A8%D8%A7%D8%B1_%D8%B3%D9%86%D8%AC%DB%8C_%D8%B4%D9%85%D8%A7%D8%B1%D9%87_%D9%85%D9%88%D8%A8%D8%A7%DB%8C%D9%84_%D9%87%D8%A7%DB%8C_%D8%A7%DB%8C%D8%B1%D8%A7%D9%86_%D8%A8%D8%A7_PHP
        let mobile = $.trim($('input[name=mobile]').val());
        let patt = new RegExp(/^09[0-9]{9}$/gm);
        if (!patt.test(mobile)) {
            alertBox.html("خطا : لطفا شماره همراه را به درستی وارد کنید");
            return false;
        }

        // User Picture Place
        let picture_place = $.trim($('input[name=picture_place]').val());
        if (picture_place.length === 0) {
            alertBox.html("خطا : لطفا محل عکس را وارد نمایید");
            return false;
        }

        // Check instagram User
        var instagram_match = new RegExp(/@([a-zA-Z0-9])+/g);
        // https://regex101.com/r/cE0wH4/4
        var instagram_match_link = new RegExp(/^https:\/\/www\.instagram\.com\/p\//gm);
        let instagram = $.trim($('input[name=instagram]').val());
        if (!instagram_match.test(instagram)) {
            alertBox.html("خطا : آی دی اینستاگرام را به شکل صحیح وارد نمایید");
            return false;
        }

        // Check File Types and Links
        let is_one_pic = false;
        let this_file, file_upload, link_instagram_image = '';
        let mb = 3 * 1024 * 1024;
        for (let i = 1; i <= 5; i++) {

            this_file = jQuery("input[id=file_" + i + "]");
            file_upload = this_file.val();
            link_instagram_image = jQuery("input[name=link_" + i + "]").val();

            // Check empty
            if (file_upload.length > 0) {

                // Check File Extension
                let fileExtension = ['jpeg', 'jpg'];
                if (jQuery.inArray(file_upload.split('.').pop().toLowerCase(), fileExtension) === -1) {
                    alertBox.html("خطا : پسوند تمامی فایل های تصاویر می بایست jpg باشد.");
                    return false;
                }

                // Check File Size
                let f = this_file[0].files[0];
                if (f.size > mb || f.fileSize > mb) {
                    alertBox.html("خطا : حداکثر حجم هر فایل تصویر تنها می بایست 3 مگابایت باشد.");
                    return false;
                }

                // Check Picture Link
                instagram_match_link.lastIndex = 0; //@see https://www.sitepoint.com/expressions-javascript/
                if (link_instagram_image.length === 0) {
                    alertBox.html(` خطا : لطفا لینک تصویر اینستاگرام برای عکس شماره ` + i + ` را وارد نمایید. `);
                    return false;
                } else {
                    if (!instagram_match_link.test(link_instagram_image)) {
                        alertBox.html(` خطا : لطفا لینک تصویر اینستاگرام برای عکس شماره ` + i + ` را به شکل صحیح وارد نمایید. `);
                        return false;
                    }
                }

                is_one_pic = true;
            }

        }

        // check One file Uploaded From User
        if (is_one_pic === false) {
            alertBox.html(` خطا : ارسال یک تصویر برای شرکت در مسابقه الزامی می باشد `);
            return false;
        }

        // Check Captcha
        let captcha = $.trim($('input[name=captcha]').val());
        if (captcha.length === 0) {
            alertBox.html("خطا : لطفا عبارت کلیدی را وارد نمایید");
            return false;
        }

        // Check Validate Security
        jQuery.ajax({
            url: application.url + '/wp-admin/admin-ajax.php',
            type: 'POST',
            cache: false,
            dataType: "json",
            data: {
                'action': 'check_security_festival_form',
                'token': application.token,
                'mobile': mobile,
                'captcha': captcha,
                'instagram_id': instagram
            }
        }).then(
            function fulfillHandler(data) {

                if (data.error == "yes") {
                    jQuery("div#error-massage").html(data.text);
                    return false;
                } else {

                    // Send Form
                    let form_data = new FormData($("form#add_new")[0]);
                    form_data.append('action', 'send_festival_form');
                    form_data.append('token', application.token);

                    // Send Form Data in ajax
                    jQuery.ajax({
                        url: application.url + '/wp-admin/admin-ajax.php',
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        cache: false,
                        dataType: "json",
                        data: form_data,
                        success: function (data) {

                            if (data.error == "yes") {
                                jQuery("div#error-massage").html(data.text);
                            } else {
                                jQuery("div#error-massage").html("").hide();
                                jQuery("div[data-step-form=2]").html(`
                    <div class="col-md-12">
<div class="title-line" style="border-bottom: 1px solid #e3e3e3;padding-bottom: 10px;"><i class="fa fa-internet-explorer"></i>&nbsp; ارسال موفقیت آمیز</div>
<div class="text-center" style="font-size: 14px; margin:35px 0;">
<span class="text-primary">${data.text.name} ${data.text.family}</span> , اثر شما با موفقیت برای جشنواره ایران خودرو ارسال گردید
<br>
<span class="text-success">شناسه اثر شما در جشنواره : ${data.text.ID}</span>
<br>
<span class="text-muted">با تشکر</span>
</div>
</div>
<div class="clearfix"></div>
`);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            jQuery("div#error-massage").html("خطا در ارتباط با سرور ایجاد شده است لطفا دوباره تلاش کنید");
                        }
                    });

                }

            },
            function rejectHandler(jqXHR, textStatus, errorThrown) {
                jQuery("div#error-massage").html("خطا در ارتباط با سرور ایجاد شده است لطفا دوباره تلاش کنید");
            });

        // Do stuff
        return false;
    });

});

