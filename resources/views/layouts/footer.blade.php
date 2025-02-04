<footer class="site-footer new_footer_area bg_color p-4">
    <div class="new_footer_top">
        <div class="footer_bg">
            <div class="row justify-content-between">
                <div class="col-lg-3 col-md-6 d-flex justify-content-start">
                    <div class="f_widget social-widget wow fadeInLeft" data-wow-delay="0.8s"
                        style="visibility: visible; animation-delay: 0.8s; animation-name: fadeInLeft;">
                        <h3 class="f-title f_600 t_color f_size_18">Kontak Kami</h3>
                        <div id="kontak" class="f_social_icon">
                            <a href="https://chat.whatsapp.com/EG7v7NMd5BpF3QZyYX4TZ6" target="_blank" class="fab fa-whatsapp"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer_bg_one"></div>
            <div class="footer_bg_two"></div>
        </div>
    </div>
    <div class="footer_bottom">
        <div class="row align-items-center">
            <div class="col-lg-12 col-sm-12">
                <p class="mb-0 f_400">&copy; Copyright {{ now()->year }}
                    {{ env('APP_NAME') ?? 'GSS' }}</p>
            </div>
        </div>
    </div>
</footer>
