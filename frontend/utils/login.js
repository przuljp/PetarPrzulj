$(document).ready(function () {

  if (localStorage.getItem("user_token")) {
    window.location.replace("dashboard.html");
  }

  $("#login-form").submit(function (e) {
    e.preventDefault();

    let data = {
      email: $("#email").val(),
      password: $("#password").val()
    };

    RestClient.request(
      "auth/login",
      "POST",
      data,
      function (response) {
        localStorage.setItem("user_token", response.token);
        localStorage.setItem("user", JSON.stringify(response.user));
        window.location.replace("dashboard.html");
      },
      function () {
        alert("Login failed");
      }
    );
  });
});
