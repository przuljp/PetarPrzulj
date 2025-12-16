var BarberService = {

  loadAll: function () {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "barber",
      type: "GET",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("user_token")
      },
      success: function (data) {
        const list = $("#barbers-list");
        list.empty();

        data.forEach(b => {
          list.append(`
            <li class="list-group-item d-flex justify-content-between">
              ${b.name}
              <button class="btn btn-sm btn-danger delete-barber" data-id="${b.id}">
                Delete
              </button>
            </li>
          `);
        });
      }
    });
  },

  add: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "barber",
      type: "POST",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("user_token")
      },
      data: JSON.stringify(entity),
      contentType: "application/json",
      success: function () {
        BarberService.loadAll();
      }
    });
  },

  remove: function (id) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "barber/" + id,
      type: "DELETE",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("user_token")
      },
      success: function () {
        BarberService.loadAll();
      }
    });
  },

  loadForSelect: function () {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "barber",
    headers: {
      Authorization: "Bearer " + localStorage.getItem("user_token")
    },
    success: function (data) {
      const select = $("#barber-select");
      select.empty();
      select.append(`<option value="">Select barber</option>`);

      data.forEach(b => {
        select.append(`
          <option value="${b.id}">
            ${b.name}
          </option>
        `);
      });
    },
    error: function () {
      alert("Failed to load barbers");
    }
  });
}

};


