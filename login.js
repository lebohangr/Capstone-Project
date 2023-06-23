const users = [
	{ username: "mahluli", password: "user"},
    { username: "khumo", password: "12345" },
    { username: "lebo", password: "2468" }
];

function login()
{
	var userName = document.getElementById("name").value;
	var passWord = document.getElementById("pswd").value;

	users.forEach(function ({ username, password }) {
        if (username.toLowerCase() === userName.toLowerCase()) {
            if (password === passWord){
				window.location.replace("./profile.html");
				window.location.href = "./profile.html";
				alert("Successfully logged in");
				console.log('Succesfully logged in');
				return true;
			}
			else{
				alert("Login failed, enter correct password");
			}			
        }
    });
}
