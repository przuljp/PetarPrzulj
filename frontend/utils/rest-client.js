// js/utils/rest-client.js
let RestClient = {
    get: function (url, callback, error_callback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + url,
            type: "GET",
            beforeSend: function (xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function (response) {
                if (callback) callback(response);
            },
            error: function (jqXHR) {
                if (error_callback) error_callback(jqXHR);
                else alert(jqXHR.responseText || "Error");
            }
        });
    },

    request: function (url, method, data, callback, error_callback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + url,
            type: method,
            beforeSend: function (xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            data: data, // form-encoded (kao u Labu za students CRUD)
            // contentType ostavljamo default da Flight može pokupiti $_POST
        })
        .done(function (response) {
            if (callback) callback(response);
        })
        .fail(function (jqXHR) {
            if (error_callback) error_callback(jqXHR);
            else alert(jqXHR.responseText || "Error");
        });
    },

    post: function (url, data, callback, error_callback) {
        RestClient.request(url, "POST", data, callback, error_callback);
    },

    delete: function (url, data, callback, error_callback) {
        RestClient.request(url, "DELETE", data, callback, error_callback);
    },

    patch: function (url, data, callback, error_callback) {
        RestClient.request(url, "PATCH", data, callback, error_callback);
    },

    put: function (url, data, callback, error_callback) {
        RestClient.request(url, "PUT", data, callback, error_callback);
    }
};
