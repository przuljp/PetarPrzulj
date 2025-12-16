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
            setTimeout(() => {
                if (typeof DashboardService !== "undefined") {
                    DashboardService.loadInfo();
                }
            }, 100);
        }

        /* ========= ADMIN – MANAGE APPOINTMENTS ========= */
        if (window.location.hash === "#manage-appointments") {
            setTimeout(() => {
                AppointmentService.loadAll();
            }, 100);
        }

        /* ========= CUSTOMER – BOOK ========= */
        if (window.location.hash === "#book") {
            setTimeout(() => {
                AppointmentService.loadBarbers();
            }, 100);
        }

        /* ========= CUSTOMER – PROFILE ========= */
        if (window.location.hash === "#profile") {
            setTimeout(() => {

                const user = UserService.getCurrentUser();
                if (!user) {
                    window.location.hash = "#login";
                    return;
                }

                // fill user info
                $("#profile-name").text(user.name || "User");
                $("#profile-email").text(user.email || "");

                // load user's appointments
                AppointmentService.loadForProfile(user.id);

            }, 100);
        }

        /* ========= REVIEWS ========= */
        if (window.location.hash === "#reviews") {
            setTimeout(() => {
                ReviewService.loadAll();
                ReviewService.loadBarbers();
            }, 100);
        }
    });

    /* =========================
       BOOK APPOINTMENT (CUSTOMER)
    ========================= */
    $(document).on("submit", "#book-appointment-form", function (e) {
        e.preventDefault();

        const user = UserService.getCurrentUser();
        if (!user) {
            alert("Please login first");
            window.location.hash = "#login";
            return;
        }

        const formData = Object.fromEntries(new FormData(this).entries());

        AppointmentService.create({
            user_id: user.id,
            barber_id: parseInt(formData.barber_id),
            service_id: parseInt(formData.service_id),
            appointment_date: formData.appointment_date
        });
    });

    /* =========================
       DELETE APPOINTMENT (ADMIN)
    ========================= */
    $(document).on("click", ".delete-appointment", function () {
        AppointmentService.remove($(this).data("id"));
    });

    /* =========================
       ADD REVIEW
    ========================= */
    $(document).on("submit", "#add-review-form", function (e) {
        e.preventDefault();

        const user = UserService.getCurrentUser();
        if (!user) {
            alert("Login required");
            window.location.hash = "#login";
            return;
        }

        const formData = Object.fromEntries(new FormData(this).entries());

        ReviewService.create({
            user_id: user.id,
            barber_id: parseInt(formData.barber_id),
            rating: parseFloat(formData.rating),
            comment: formData.comment
        });
    });

});
