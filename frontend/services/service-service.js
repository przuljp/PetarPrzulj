// js/services/service-service.js
var ServiceService = {

    initAdmin: function () {
        // Klik na "Manage Services" u Admin panelu
        $(document).on("click", 'a[href="#manage-services"]', function (e) {
            e.preventDefault();
            e.stopPropagation();
            ServiceService.renderAdminView();
        });
    },

    renderAdminView: function () {
        const html = `
            <h3>Manage Services</h3>
            <p class="text-muted">Create, edit and delete barber shop services.</p>

            <form id="serviceForm" class="row g-3 mb-3">
                <input type="hidden" name="id" id="service-id">
                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input class="form-control" name="name" id="service-name" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Price</label>
                    <input class="form-control" name="price" id="service-price" type="number" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Duration (min)</label>
                    <input class="form-control" name="duration" id="service-duration" type="number" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Description</label>
                    <input class="form-control" name="description" id="service-description">
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <button class="btn btn-secondary ms-2" type="button" id="service-reset-btn">Clear</button>
                </div>
            </form>

            <table class="table table-striped table-sm" id="admin-services-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Description</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        `;

        $("#admin-content").html(html);
        ServiceService.bindForm();
        ServiceService.loadServices();
    },

    bindForm: function () {
        $(document).off("submit", "#serviceForm");
        $(document).on("submit", "#serviceForm", function (e) {
            e.preventDefault();
            const formData = Object.fromEntries(new FormData(this).entries());
            const id = formData.id;

            if (!formData.name || !formData.price || !formData.duration) {
                alert("Name, price and duration are required.");
                return;
            }

            if (id) {
                ServiceService.updateService(id, formData);
            } else {
                ServiceService.createService(formData);
            }
        });

        $(document).off("click", "#service-reset-btn");
        $(document).on("click", "#service-reset-btn", function () {
            ServiceService.clearForm();
        });

        // Edit/Delete dugmad (delegirano)
        $(document).off("click", ".service-edit-btn");
        $(document).on("click", ".service-edit-btn", function () {
            const id = $(this).data("id");
            ServiceService.fillFormFromRow(id);
        });

        $(document).off("click", ".service-delete-btn");
        $(document).on("click", ".service-delete-btn", function () {
            const id = $(this).data("id");
            if (confirm("Delete this service?")) {
                ServiceService.deleteService(id);
            }
        });
    },

    clearForm: function () {
        $("#service-id").val("");
        $("#service-name").val("");
        $("#service-price").val("");
        $("#service-duration").val("");
        $("#service-description").val("");
    },

    loadServices: function () {
        RestClient.get("service", function (data) {
            // pretpostavka: API vraća niz objekata: [{id, name, price, duration, description}, ...]
            const $tbody = $("#admin-services-table tbody");
            $tbody.empty();

            if (!Array.isArray(data)) {
                console.warn("Unexpected services response", data);
                return;
            }

            data.forEach(function (s, index) {
                const row = `
                    <tr data-id="${s.id}">
                        <td>${index + 1}</td>
                        <td class="svc-name">${s.name}</td>
                        <td class="svc-price">${s.price}</td>
                        <td class="svc-duration">${s.duration}</td>
                        <td class="svc-description">${s.description || ""}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary service-edit-btn" data-id="${s.id}">Edit</button>
                            <button class="btn btn-sm btn-outline-danger service-delete-btn ms-1" data-id="${s.id}">Delete</button>
                        </td>
                    </tr>
                `;
                $tbody.append(row);
            });
        }, function (xhr) {
            alert(xhr.responseText || "Failed to load services");
        });
    },

    fillFormFromRow: function (id) {
        const $row = $('#admin-services-table tbody tr[data-id="' + id + '"]');
        if ($row.length === 0) return;

        $("#service-id").val(id);
        $("#service-name").val($row.find(".svc-name").text());
        $("#service-price").val($row.find(".svc-price").text());
        $("#service-duration").val($row.find(".svc-duration").text());
        $("#service-description").val($row.find(".svc-description").text());
    },

    createService: function (service) {
        RestClient.post("service", service, function () {
            ServiceService.clearForm();
            ServiceService.loadServices();
        }, function (xhr) {
            alert(xhr.responseText || "Create service failed");
        });
    },

    updateService: function (id, service) {
        RestClient.patch("service/" + id, service, function () {
            ServiceService.clearForm();
            ServiceService.loadServices();
        }, function (xhr) {
            alert(xhr.responseText || "Update service failed");
        });
    },

    deleteService: function (id) {
        RestClient.delete("service/" + id, null, function () {
            ServiceService.loadServices();
        }, function (xhr) {
            alert(xhr.responseText || "Delete service failed");
        });
    }
};
