$(document).ready(function() {
    $("#do-login").click(function() {

        __request("api.v1.login.salt", {}, function(res) {
            console.debug(res);
            var salt = res.data;

            var username = $("#username").val();
            var password = $("#password").val();
            username = username.trim();
            password = password.trim();
            var cipher = md5(username + salt + password);
            __request("api.v1.login.login", {username: username, cipher: cipher}, function(res) {
                console.debug(res);
                if (res.data.ret == "fail") {
                    alert(res.data.reason);
                } else {
                    var refer = res.data.refer;
                    console.debug(refer);
                    document.location.href = refer;
                }
            });
        });
    });
});

