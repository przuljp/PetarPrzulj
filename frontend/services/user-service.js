// js/services/user-service.js
var UserService = {

    init: function () {
        // Login forma (delegirano - radi i kad se view dinamički učita)
        $(document).on("submit", "#loginForm", function (e) {
            e.preventDefault();
            const entity = Object.fromEntries(new FormData(this).entries());
            UserService.login(entity);
        });

        // Register forma
        $(document).on("submit", "#registerForm", function (e) {
            e.preventDefault();
            const entity = Object.fromEntries(new FormData(this).entries());
            UserService.register(entity);
        });

        // Logout dugme (stavićemo id="logoutBtn" u navbaru dinamički)
        $(document).on("click", "#logoutBtn", function (e) {
            e.preventDefault();
            UserService.logout();
        });

        // Inicijalno popuni navbar & admin UI
        UserService.updateNavbar();
        UserService.applyRoleUI();

        // Godina u footeru (čisto kozmetika)
        const yearEl = document.getElementById("year");
        if (yearEl) {
            yearEl.textContent = new Date().getFullYear();
        }
    },

    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                // backend vraća { message, data: { ...userFields..., token } }
                localStorage.setItem("user_token", result.data.token);
                UserService.updateNavbar();
                UserService.applyRoleUI();
                window.location.hash = "#home";
            },
            error: function (xhr) {
                alert(xhr.responseText || "Login error");
            }
        });
    },

    register: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                // Nakon uspješnog registra, možeš ili auto-login ili redirect na login
                alert("Registration successful. Please log in.");
                window.location.hash = "#login";
            },
            error: function (xhr) {
                alert(xhr.responseText || "Registration error");
            }
        });
    },

    logout: function () {
        localStorage.removeItem("user_token");
        UserService.updateNavbar();
        UserService.applyRoleUI();
        window.location.hash = "#login";
    },

    getCurrentUser: function () {
        const token = localStorage.getItem("user_token");
        if (!token) return null;
        const payload = Utils.parseJwt(token);
        return payload ? payload.user : null;
    },

    updateNavbar: function () {
    const $tabs = $("#tabs");
    if ($tabs.length === 0) return;

    $tabs.empty();

    const token = localStorage.getItem("user_token");

    /* ============================
       GUEST
    ============================ */
    if (!token) {
        $tabs.append(`
            <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="#barbers">Barbers</a></li>
            <li class="nav-item"><a class="nav-link" href="#reviews">Reviews</a></li>
            <li class="nav-item d-flex align-items-center">
                <a class="btn btn-outline-light me-2" href="#login">Login</a>
                <a class="btn btn-light" href="#register">Register</a>
            </li>
        `);
        return;
    }

    const user = UserService.getCurrentUser();
    if (!user) return;

    /* ============================
       ADMIN
    ============================ */
    if (user.role === Constants.ADMIN_ROLE) {
    $tabs.append(`
        <li class="nav-item">
            <a class="nav-link" href="#dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#manage-barbers">Barbers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#manage-appointments">Appointments</a>
        </li>
        <li class="nav-item d-flex align-items-center">
            <span class="navbar-text me-2">Admin</span>
            <button id="logoutBtn" class="btn btn-outline-light">Logout</button>
        </li>
    `);
    return;
}


    /* ============================
       CUSTOMER
    ============================ */
    $tabs.append(`
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#barbers">Barbers</a></li>
        <li class="nav-item"><a class="nav-link" href="#reviews">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="#book">Book</a></li>
        <li class="nav-item"><a class="nav-link" href="#profile">Profile</a></li>
        <li class="nav-item d-flex align-items-center">
            <span class="navbar-text me-2">${user.name}</span>
            <button id="logoutBtn" class="btn btn-outline-light">Logout</button>
        </li>
    `);
}

    ,

    applyRoleUI: function () {
    const user = UserService.getCurrentUser();
    if (!user) return; // ⬅ nema redirecta

    const role = user.role;

    if (role === Constants.ADMIN_ROLE) {
        $(".admin-only").removeClass("d-none");
        $("#admin-denied").addClass("d-none");
    } else {
        $(".admin-only").addClass("d-none");
        $("#admin-denied").removeClass("d-none");
    }
}

};
