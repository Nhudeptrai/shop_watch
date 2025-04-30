<div class="back-to-top" onClick="backToTop()">
  <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>

<script>
  window.onscroll = () => {
    document.querySelector(".back-to-top").style.display = (scrollY < 300) ? "none" : "initial";
  }

  function backToTop() {
    scrollTo({top: 0, behavior: 'smooth'});
  }
</script>