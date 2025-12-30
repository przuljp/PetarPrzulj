// frontend/js/script.js
$(document).ready(function () {

    var app = $.spapp({
        defaultView: "home",
        templateDir: "views/"
    });

    app.route({
        view: "home",
        load: "home.html",
        onReady: function () {
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "services",
        load: "services.html",
        onReady: function () {
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "barbers",
        load: "barbers.html",
        onReady: function () {
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "reviews",
        load: "reviews.html",
        onReady: function () {
            ReviewService.initReviewForm();
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "book",
        load: "book.html",
        onReady: function () {
            AppointmentService.initBookingForm();
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "profile",
        load: "profile.html",
        onReady: function () {
            AppointmentService.loadProfile();
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "login",
        load: "login.html",
        onReady: function () {
            UserService.initLoginValidation();
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "register",
        load: "register.html",
        onReady: function () {
            UserService.initRegisterValidation();
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "dashboard",
        load: "dashboard.html",
        onReady: function () {
            DashboardService.loadInfo();
            UserService.applyRoleUI();
        }
    });

    app.route({
        view: "manage-appointments",
        load: "manage-appointments.html",
        onReady: function () {
            AppointmentService.loadAll();
            UserService.applyRoleUI();
        }
    });

    app.run();
});
