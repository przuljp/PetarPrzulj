// frontend/services/user-service.js
var UserService = {

    init: function () {

        /* LOGOUT */
        $(document).off("click", "#logoutBtn");
        $(document).on("click", "#logoutBtn", function (e) {
            e.preventDefault();
            UserService.logout();
        });

        /* INIT UI */
        UserService.updateNavbar();
        UserService.applyRoleUI();

        const yearEl = document.getElementById("year");
        if (yearEl) yearEl.textContent = new Date().getFullYear();
    },

    /* =========================
       LOGIN VALIDATION
    ========================= */
    initLoginValidation: function () {
        if (!$("#loginForm").length || $("#loginForm").data("validator")) return;

        $("#loginForm").validate({
            rules: {
                email: { required: true, email: true },
                password: { required: true, minlength: 8 }
            },
            submitHandler: function (form) {
                const entity = Object.fromEntries(new FormData(form).entries());
                UserService.login(entity);
            }
        });
    },

    /* =========================
       REGISTER VALIDATION
    ========================= */
    initRegisterValidation: function () {
        if (!$("#registerForm").length || $("#registerForm").data("validator")) return;

        $("#registerForm").validate({
            rules: {
                name: { required: true, minlength: 2 },
                email: { required: true, email: true },
                password: { required: true, minlength: 8 }
            },
            submitHandler: function (form) {
                const entity = Object.fromEntries(new FormData(form).entries());
                UserService.register(entity);
            }
        });
    },

    /* =========================
       LOGIN
    ========================= */
    login: function (entity) {
        $.blockUI({ message: "<h3>Logging in...</h3>" });

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(entity),
            success: function (result) {
                localStorage.setItem("user_token", result.data.token);
                UserService.updateNavbar();
                UserService.applyRoleUI();
                window.location.hash = "#home";
            },
            error: function (xhr) {
                alert(xhr.responseText || "Login failed");
            },
            complete: function () {
                $.unblockUI();
            }
        });
    },

    /* =========================
       REGISTER
    ========================= */
    register: function (entity) {
        $.blockUI({ message: "<h3>Creating account...</h3>" });

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(entity),
            success: function () {
                alert("Registration successful. Please login.");
                window.location.hash = "#login";
            },
            error: function (xhr) {
                alert(xhr.responseText || "Registration failed");
            },
            complete: function () {
                $.unblockUI();
            }
        });
    },

    /* =========================
       LOGOUT
    ========================= */
    logout: function () {
        localStorage.removeItem("user_token");
        UserService.updateNavbar();
        UserService.applyRoleUI();
        window.location.hash = "#login";
    },

    /* =========================
       CURRENT USER
    ========================= */
    getCurrentUser: function () {
        const token = localStorage.getItem("user_token");
        if (!token) return null;

        const payload = Utils.parseJwt(token);
        return payload ? payload.user : null;
    },

    /* =========================
       NAVBAR
    ========================= */
    updateNavbar: function () {
        const $tabs = $("#tabs");
        if (!$tabs.length) return;

        $tabs.empty();
        const token = localStorage.getItem("user_token");

        if (!token) {
            $tabs.append(`
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#barbers">Barbers</a></li>
                <li class="nav-item"><a class="nav-link" href="#reviews">Reviews</a></li>
                <li class="nav-item">
                    <a class="btn btn-outline-light me-2" href="#login">Login</a>
                    <a class="btn btn-light" href="#register">Register</a>
                </li>
            `);
            return;
        }

        const user = UserService.getCurrentUser();
        if (!user) return;

        if (user.role === Constants.ADMIN_ROLE) {
            $tabs.append(`
                <li class="nav-item"><a class="nav-link" href="#dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="#manage-appointments">Appointments</a></li>
                <li class="nav-item">
                    <span class="navbar-text me-2">Admin</span>
                    <button id="logoutBtn" class="btn btn-outline-light">Logout</button>
                </li>
            `);
            return;
        }

        $tabs.append(`
            <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="#barbers">Barbers</a></li>
            <li class="nav-item"><a class="nav-link" href="#reviews">Reviews</a></li>
            <li class="nav-item"><a class="nav-link" href="#book">Book</a></li>
            <li class="nav-item"><a class="nav-link" href="#profile">Profile</a></li>
            <li class="nav-item">
                <span class="navbar-text me-2">${user.name}</span>
                <button id="logoutBtn" class="btn btn-outline-light">Logout</button>
            </li>
        `);
    },

    /* =========================
       ROLE UI
    ========================= */
    applyRoleUI: function () {
        const user = UserService.getCurrentUser();
        if (!user) {
            $(".admin-only").addClass("d-none");
            return;
        }

        if (user.role === Constants.ADMIN_ROLE) {
            $(".admin-only").removeClass("d-none");
            $("#admin-denied").addClass("d-none");
        } else {
            $(".admin-only").addClass("d-none");
            $("#admin-denied").removeClass("d-none");
        }
    }
};
