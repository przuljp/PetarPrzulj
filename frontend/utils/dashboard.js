$(document).ready(function () {

  if (!localStorage.getItem("user_token")) {
    window.location.replace("login.html");
    return;
  }

  let user = JSON.parse(localStorage.getItem("user"));

  // sakrij sve role-based elemente
  $(".admin-only").hide();
  $(".customer-only").hide();

  if (user.role === Constants.ROLE_ADMIN) {
    $(".admin-only").show();
  }

  if (user.role === Constants.ROLE_CUSTOMER) {
    $(".customer-only").show();
  }

  $("#logout").click(function () {
    localStorage.clear();
    window.location.replace("login.html");
  });

});

