<section class="slider m-header">
  <div class="flexslider">
    <ul class="slides">
      <li><img src="images/banners/banner-petite.png" alt="" class="h-75 w-full object-cover" /></li>
      <li><img src="images/banners/banner-lumine.png" alt="" class="h-75 w-full object-cover"/></li>
      <li><img src="images/banners/banner-classic.png" alt="" class="h-75 w-full object-cover"/></li>
    </ul>
  </div>
</section>

<script type="text/javascript">
  $(window).load(function(){
    $('.flexslider').flexslider({
    animation: "slide",
    start: function(slider){
      $('body').removeClass('loading');
    }
    });
  });
</script>