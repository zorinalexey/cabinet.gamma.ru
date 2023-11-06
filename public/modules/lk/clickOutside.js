export default {
  function(btn, item) {
    console.log(btn, item);
    btn.classList.remove("open");
    item.classList.remove("open");
  },
};
