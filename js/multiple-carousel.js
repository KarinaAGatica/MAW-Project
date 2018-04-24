$(document).ready(function(){
    $('.owl-one').owlCarousel({
        loop:true,
    margin:0,
    autoplay:true,
    autoplayTimeout:3000,
    nav:true,
    navText:['<i class="icon-next-1 "></i>', '<i class="icon-next"></i>'],
        responsive: {
            0: {
                items: 1,
                
            },
            600: {
                items: 1,
                
            },
            1000: {
                items: 1,
                
            },
            1200: {
                items: 1,
                
            },
            1400: {
                items: 1,
                
            },
            1600: {
                items: 1,
                
            },
            1800: {
                items: 1,
                
            }
        }
    });

    $('.owl-two').owlCarousel({

       loop:true,
    margin:0,
    autoplay:true,
    autoplayTimeout:3000,

    nav:true,
    navText:['<i class="icon-next-1"></i>', '<i class="icon-next"></i>'],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });

    
});