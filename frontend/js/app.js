// js/app.js
$(function () {

    /* =========================
       INIT AUTH / NAVBAR
    ========================= */
    UserService.init();

    /* =========================
       SPApp VIEW CHANGES
    ========================= */
    $(window).on("hashchange", function () {

        UserService.applyRoleUI();

        /* ========= ADMIN DASHBOARD ========= */
        if (window.location.hash === "#dashboard") {
            DashboardService.loadInfo();
        }

        /* ========= ADMIN – MANAGE APPOINTMENTS ========= */
        if (window.location.hash === "#manage-appointments") {
            AppointmentService.loadAll();
        }

        /* ========= CUSTOMER – BOOK ========= */
        if (window.location.hash === "#book") {
            AppointmentService.initBookingForm();
        }

        /* ========= CUSTOMER – PROFILE ========= */
        if (window.location.hash === "#profile") {
            AppointmentService.loadProfile();
        }

        /* ========= REVIEWS ========= */
        if (window.location.hash === "#reviews") {
            ReviewService.initReviewForm();
        }
    });

});
