// frontend/js/services/appointment-service.js
var AppointmentService = {

    /* =========================
       INIT BOOKING FORM
    ========================= */
    initBookingForm: function () {
        AppointmentService.loadBarbers();
        AppointmentService.setMinimumAppointmentDate();

        $("#book-appointment-form").validate({
            rules: {
                barber_id: { required: true },
                service_id: { required: true },
                appointment_date: { required: true, minAppointmentDate: true }
            },
            messages: {
                barber_id: "Please select a barber",
                service_id: "Please select a service",
                appointment_date: {
                    required: "Please choose a date",
                    minAppointmentDate: "Appointment date cannot be in the past"
                }
            },
            submitHandler: function (form) {

                const user = UserService.getCurrentUser();
                if (!user) {
                    alert("Login required");
                    window.location.hash = "#login";
                    return;
                }

                const data = Object.fromEntries(new FormData(form).entries());

                AppointmentService.create({
                    user_id: user.id,
                    barber_id: parseInt(data.barber_id),
                    service_id: parseInt(data.service_id),
                    appointment_date: data.appointment_date
                });
            }
        });

        $(document).off("click", ".delete-appointment");
        $(document).on("click", ".delete-appointment", function () {
            AppointmentService.remove($(this).data("id"));
        });
    },

    setMinimumAppointmentDate: function () {
        const now = new Date();
        const localISOTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
            .toISOString()
            .slice(0, 16);

        $("input[name='appointment_date']").attr("min", localISOTime);

        if (!$.validator.methods.minAppointmentDate) {
            $.validator.addMethod("minAppointmentDate", function (value) {
                if (!value) return true;
                return new Date(value).getTime() >= Date.now();
            });
        }
    },

    /* =========================
       LOAD ALL (ADMIN)
    ========================= */
    loadAll: function () {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointment",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const list = $("#appointments-list");
                if (!list.length) return;

                list.empty();

                if (!data.length) {
                    list.append(`<li class="list-group-item text-muted">No appointments.</li>`);
                    return;
                }

                data.forEach(a => {
                    list.append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${a.appointment_date}</strong><br>
                                Barber ID: ${a.barber_id}<br>
                                Service ID: ${a.service_id}
                            </div>
                            <button class="btn btn-sm btn-danger delete-appointment"
                                    data-id="${a.id}">
                                Delete
                            </button>
                        </li>
                    `);
                });
            }
        });
    },

    /* =========================
       PROFILE VIEW
    ========================= */
    loadProfile: function () {
        const user = UserService.getCurrentUser();
        if (!user) {
            window.location.hash = "#login";
            return;
        }

        $("#profile-name").text(user.name);
        $("#profile-email").text(user.email);

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointment?user_id=" + user.id,
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const list = $("#profile-appointments");
                list.empty();

                if (!data.length) {
                    list.append(`<li class="list-group-item text-muted">No appointments.</li>`);
                    return;
                }

                data.forEach(a => {
                    list.append(`
                        <li class="list-group-item">
                            <strong>${a.appointment_date}</strong><br>
                            Barber ID: ${a.barber_id}<br>
                            Service ID: ${a.service_id}
                        </li>
                    `);
                });
            }
        });
    },

    /* =========================
       CREATE
    ========================= */
    create: function (payload) {
        $.blockUI({ message: "<h3>Booking appointment...</h3>" });

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointment",
            type: "POST",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            contentType: "application/json",
            data: JSON.stringify(payload),
            success: function () {
                alert("Appointment booked successfully!");
                window.location.hash = "#profile";
            },
            error: function () {
                alert("Failed to book appointment");
            },
            complete: function () {
                $.unblockUI();
            }
        });
    },

    /* =========================
       DELETE
    ========================= */
    remove: function (id) {
        $.blockUI({ message: "<h3>Deleting...</h3>" });

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointment/" + id,
            type: "DELETE",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function () {
                AppointmentService.loadAll();
            },
            complete: function () {
                $.unblockUI();
            }
        });
    },

    /* =========================
       LOAD BARBERS
    ========================= */
    loadBarbers: function () {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "barber",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const select = $("#barber-select");
                select.empty();
                select.append(`<option value="">Choose barber</option>`);

                data.forEach(b => {
                    select.append(`<option value="${b.id}">${b.name}</option>`);
                });
            }
        });
    }
};
