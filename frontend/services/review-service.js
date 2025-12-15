var ReviewService = {

    /* =========================
       LOAD ALL REVIEWS
    ========================= */
    loadAll: function () {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "review",
            type: "GET",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const list = $("#reviews-list");
                if (!list.length) return;

                list.empty();

                data.forEach(r => {
                    list.append(`
                        <li class="list-group-item">
                            <strong>Rating:</strong> ${r.rating} ⭐<br>
                            <small class="text-muted">${r.comment}</small>
                        </li>
                    `);
                });
            }
        });
    },

    /* =========================
       CREATE REVIEW (CUSTOMER)
    ========================= */
    create: function (payload) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "review",
            type: "POST",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            contentType: "application/json",
            data: JSON.stringify(payload),
            success: function () {
                alert("Review submitted!");
                ReviewService.loadAll();
                $("#add-review-form")[0].reset();
            },
            error: function (xhr) {
                alert(xhr.responseText || "Failed to submit review");
            }
        });
    },

    /* =========================
       LOAD BARBERS INTO SELECT
    ========================= */
    loadBarbers: function () {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "barber",
            type: "GET",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const select = $("#review-barber-select");
                if (!select.length) return;

                select.empty();
                select.append(`<option value="">Choose barber</option>`);

                data.forEach(b => {
                    select.append(`<option value="${b.id}">${b.name}</option>`);
                });
            }
        });
    }
};
