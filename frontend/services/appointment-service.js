// services/appointment-service.js
var AppointmentService = {

    /* =========================
       LOAD APPOINTMENTS (ADMIN / FILTERED)
    ========================= */
    loadAll: function (filters = {}) {
        let query = "";

        if (filters.user_id) {
            query = "?user_id=" + filters.user_id;
        } else if (filters.barber_id) {
            query = "?barber_id=" + filters.barber_id;
        }

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointment" + query,
            type: "GET",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const list = $("#appointments-list");
                if (!list.length) return;

                list.empty();

                if (!data.length) {
                    list.append(`
                        <li class="list-group-item text-muted">
                            No appointments found.
                        </li>
                    `);
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
            },
            error: function (xhr) {
                console.error("Failed to load appointments", xhr.responseText);
            }
        });
    },

    /* =========================
       CUSTOMER – CREATE APPOINTMENT
    ========================= */
    create: function (payload) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointment",
            type: "POST",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            contentType: "application/json",
            data: JSON.stringify(payload),
            success: function () {
                alert("Appointment successfully booked!");
                window.location.hash = "#profile";
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("Failed to book appointment");
            }
        });
    },

    /* =========================
       ADMIN – DELETE APPOINTMENT
    ========================= */
    remove: function (id) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointment/" + id,
            type: "DELETE",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function () {
                AppointmentService.loadAll();
            }
        });
    },

    /* =========================
       LOAD BARBERS (BOOK PAGE)
    ========================= */
    loadBarbers: function () {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "barber",
            type: "GET",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const select = $("#barber-select");
                if (!select.length) return;

                select.empty();
                select.append(`<option value="">Choose barber</option>`);

                data.forEach(b => {
                    select.append(`<option value="${b.id}">${b.name}</option>`);
                });
            },
            error: function () {
                $("#barber-select").html(
                    `<option value="">Failed to load barbers</option>`
                );
            }
        });
    },

    /* =========================
       LOAD APPOINTMENTS FOR PROFILE (CUSTOMER)
    ========================= */
    loadForProfile: function (user_id) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointment?user_id=" + user_id,
            type: "GET",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const list = $("#profile-appointments");
                if (!list.length) return;

                list.empty();

                if (!data.length) {
                    list.append(`
                        <li class="list-group-item text-muted">
                            You have no appointments yet.
                        </li>
                    `);
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
            },
            error: function (xhr) {
                console.error("Failed to load profile appointments", xhr.responseText);
                $("#profile-appointments").html(`
                    <li class="list-group-item text-danger">
                        Failed to load appointments.
                    </li>
                `);
            }
        });
    }
};
