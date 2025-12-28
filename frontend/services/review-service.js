// frontend/js/services/review-service.js
var ReviewService = {

    initReviewForm: function () {
        ReviewService.loadAll();
        ReviewService.loadBarbers();

        $("#add-review-form").validate({
            rules: {
                barber_id: { required: true },
                rating: {
                    required: true,
                    min: 1,
                    max: 5
                },
                comment: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                barber_id: "Please select a barber",
                rating: "Rating must be between 1 and 5",
                comment: "Comment must be at least 5 characters"
            },
            submitHandler: function (form) {

                const user = UserService.getCurrentUser();
                if (!user) {
                    alert("Login required");
                    window.location.hash = "#login";
                    return;
                }

                const data = Object.fromEntries(new FormData(form).entries());

                ReviewService.create({
                    user_id: user.id,
                    barber_id: parseInt(data.barber_id),
                    rating: parseFloat(data.rating),
                    comment: data.comment
                });
            }
        });
    },

    loadAll: function () {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "review",
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
                            <strong>${r.rating} ⭐</strong><br>
                            ${r.comment}
                        </li>
                    `);
                });
            }
        });
    },

    create: function (payload) {
        $.blockUI({ message: "<h3>Submitting review...</h3>" });

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
                $("#add-review-form")[0].reset();
                ReviewService.loadAll();
            },
            error: function () {
                alert("Failed to submit review");
            },
            complete: function () {
                $.unblockUI();
            }
        });
    },

    loadBarbers: function () {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "barber",
            headers: {
                Authorization: "Bearer " + localStorage.getItem("user_token")
            },
            success: function (data) {
                const select = $("#review-barber-select");
                select.empty();
                select.append(`<option value="">Choose barber</option>`);

                data.forEach(b => {
                    select.append(`<option value="${b.id}">${b.name}</option>`);
                });
            }
        });
    }
};
