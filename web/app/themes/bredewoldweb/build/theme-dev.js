/****************************************************************************/
         /*C:\xampp\htdocs\bredewoldweb/web/app/themes/bredewoldweb/js/theme.js */
         /***************************************************************************/
         jQuery(document).ready(function(){

});/****************************************************************************/
         /*C:\xampp\htdocs\bredewoldweb/web/app/themes/bredewoldweb/js/animations.js */
         /***************************************************************************/
         jQuery(document).ready(function(){

  //fadeUp
  let elementFadeUp = document.querySelectorAll(".fadeUp");

  console.log(elementFadeUp);
  window.addEventListener('scroll', fadeUp );

  function fadeUp() {
    for ( var i = 0; i < elementFadeUp.length; i++ ) {
      var elem = elementFadeUp[i];
      var distInView = elem.getBoundingClientRect().top - window.innerHeight + 100;
      if (distInView < 0) {
        elem.classList.add("inView");
      } else {
        elem.classList.remove("inView");
      }
    }
  }
  fadeUp();

  //fadePop
  let elementFadeRight = document.querySelectorAll(".fadeRight");

  console.log(elementFadeRight);
  window.addEventListener('scroll', fadeRight );

  function fadeRight() {
    for ( var i = 0; i < elementFadeRight.length; i++ ) {
      var elem = elementFadeRight[i];
      var distInView = elem.getBoundingClientRect().top - window.innerHeight + 0;
      if (distInView < 0) {
        elem.classList.add("inView");
      } else {
        elem.classList.remove("inView");
      }
    }
  }
  fadeRight();

});