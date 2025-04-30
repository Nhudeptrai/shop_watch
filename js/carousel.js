$(document).ready(($) => {
    // Khởi tạo Slick Slider cho sản phẩm mới
    $('.product-slider').slick({
      infinite: true,
      slidesToShow: 4,
      slidesToScroll: 1,
      speed: 300,
      autoplay: true,
      autoplaySpeed: 2000,
      arrow: true,
      prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
      nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
  
      responsive: [
        {
          breakpoint: 1280,
          settings: {
            slidesToShow: 3
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 2
          }
        },
        {
          breakpoint: 640,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });
  });