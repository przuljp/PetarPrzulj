$(document).ready(function(){
var app = $.spapp({
    defaultView: "home",
    templateDir: "views/"

});

app.route({
    view:"home",
    load:"home.html"
});

app.route({
    view:"services",
    load:"services.html"
});

app.route({
    view:"barbers",
    load:"barbers.html"
});

app.route({
    view:"reviews",
    load:"reviews.html"
});

app.route({
    view:"book",
    load:"book.html"
});

app.route({
    view:"profile",
    load:"profile.html"
});

app.route({
    view:"register",
    load:"register.html"
});

app.route({
    view:"login",
    load:"login.html"
});

app.run();
});