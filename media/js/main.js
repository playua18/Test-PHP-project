var addcomment = document.querySelector('#show-form-comment');
var commentform = document.querySelector('.comment-add-form--hide');

addcomment.addEventListener("click", function (event) {
  event.preventDefault();
  commentform.classList.toggle("comment-add-form--hide");
  // commentform.classList.add("comment-add-form--show");
});
// closemap.addEventListener("click", function (event) {
//   event.preventDefault();
//   popupmap.classList.remove("modal-content-map-show");
//   overlay.classList.remove("overlay-show");
// });
