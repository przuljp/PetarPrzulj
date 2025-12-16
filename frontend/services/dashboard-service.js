console.log("DashboardService loaded");

var DashboardService = {



  loadInfo: function () {
    const token = localStorage.getItem("user_token");
    if (!token) return;

    const payload = Utils.parseJwt(token);

    $("#admin-email").text(payload.user.email);
    $("#admin-role").text(payload.user.role);
  }
};
