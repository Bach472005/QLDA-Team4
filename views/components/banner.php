<style>
  .banner {
    width: 100%;
    overflow: hidden;
    position: relative;
    text-align: center;
    margin-bottom: 40px;
}

.banner .slide {
    display: none;
}

.banner .slide img {
    width: 100%;
    height: auto;
    border-radius: 10px;
}

.banner .slide:first-child {
    display: block;
}
</style>

<section class="banner">
   <div class="slide active"> <img src="public/images/banner/baner2.jpg" alt="Banner 2" /> </div>
   <div class="slide"> <img src="public/images/banner/baner3.jpg" alt="Banner 3" /> </div>
</section>


 <script>
    document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".banner .slide");
    let currentIndex = 0;
    const intervalTime = 3000; // Thời gian chuyển slide (3 giây)

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.display = i === index ? "block" : "none";
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }

    // Hiển thị slide đầu tiên và bắt đầu tự động chuyển
    showSlide(currentIndex);
    setInterval(nextSlide, intervalTime);
});

 </script>