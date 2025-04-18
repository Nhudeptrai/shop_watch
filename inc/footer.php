<div class="footer">
    <div class="wrapper">	
        <div class="section group">
            <div class="col_1_of_4 span_1_of_4">
                <h4>Thông tin</h4>
                <ul>
                    <li><a href="#">Về chúng tôi</a></li>
                    <li><a href="#">Dịch vụ khách hàng</a></li>
                    <li><a href="#"><span>Tìm kiếm nâng cao</span></a></li>
                    <li><a href="#">Đơn hàng và trả hàng</a></li>
                    <li><a href="#"><span>Liên hệ</span></a></li>
                </ul>
            </div>
            <div class="col_1_of_4 span_1_of_4">
                <h4>Từ chúng tôi</h4>
                <ul>
                    <li><a href="about.html">Về chúng tôi</a></li>
                    <li><a href="faq.html">Dịch vụ khách hàng</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="contact.html"><span>Sơ đồ trang web</span></a></li>
                    <li><a href="preview.html"><span>Điều khoản tìm kiếm</span></a></li>
                </ul>
            </div>
            <div class="col_1_of_4 span_1_of_4">
                <h4>Tài khoản của tôi</h4>
                <ul>
                    <li><a href="contact.html">Đăng nhập</a></li>
                    <li><a href="index.html">Xem giỏ hàng</a></li>
                    <li><a href="#">Danh sách yêu thích</a></li>
                    <li><a href="#">Theo dõi đơn hàng</a></li>
                    <li><a href="faq.html">Hỗ trợ</a></li>
                </ul>
            </div>
            <div class="col_1_of_4 span_1_of_4">
                <h4>Liên hệ</h4>
                <ul>
                    <li><span>+84-333732439</span></li>
                    <li><span>+84-123456789</span></li>
                </ul>
                <div class="social-icons">
                    <h4>Theo dõi chúng tôi</h4>
                    <ul>
                        <li class="facebook"><a href="#" target="_blank"> </a></li>
                        <li class="twitter"><a href="#" target="_blank"> </a></li>
                        <li class="googleplus"><a href="#" target="_blank"> </a></li>
                        <li class="contact"><a href="#" target="_blank"> </a></li>
                        <div class="clear"></div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copy_right">
            <p>Website bán đồng hồ nhóm 14 </p>
        </div>
    </div>
</div>
    <script type="text/javascript">
		$(document).ready(function() {
			/*
			var defaults = {
	  			containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
	 		};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
		});
	</script>
    <a href="#" id="toTop" style="display: block;"><span id="toTopHover" style="opacity: 1;"></span></a>
    <link href="css/flexslider.css" rel='stylesheet' type='text/css' />
	  <script defer src="js/jquery.flexslider.js"></script>
	  <script type="text/javascript">
		$(function(){
		  SyntaxHighlighter.all();
		});
		$(window).load(function(){
		  $('.flexslider').flexslider({
			animation: "slide",
			start: function(slider){
			  $('body').removeClass('loading');
			}
		  });
		});
	  </script>
</body>
</html>