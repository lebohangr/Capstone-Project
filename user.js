class GeneralUser{
    /** Search for a research using it's
      * Research title, year it was published, author/co-author name
      */
    searchForResearch(researchTitle, yearOfPublication, authorName){
        console.log("Searching for a research...");
    }

    // Generate a report 
    generateReport(){
        console.log("Generating a report...");
    }

    // Download a research paper
    downloadResearchPaper(){
        console.log("Downloading a paper");
    }

    // View a research paper
    viweResearchPaper(){
        console.log("Viewing the research paper");
    }
}

class Cair extends GeneralUser{
    constructor(username, password ,fullname){
        super();
        this.username = username;
        this.password = password;
        this.email = fullname;
    }

    /** Loop through the array of registered user's
      * and validate the user's credentials
      * with the ones stored in the system
      */
    login(userName, passWord){
    	var userName = document.getElementById("name").value;
	var passWord = document.getElementById("pswd").value;
	
	users.forEach(function ({ username, password }) {
        if (username.toLowerCase() === userName.toLowerCase()) {
            if (password === passWord){
				window.location.replace("./profile.html");
				window.location.href = "./profile.html";
				alert("Successfully logged in");
				return true;
			}
			else{
				alert("Login failed, enter correct password");
			}
            }
   	});
    }

    uploadPaper(fileName){
        if(fileName.endsWith(".pdf" ) || fileName.endsWith(".txt" )){
            researchText.innerHTML = fileName;
        }
        else{
            researchText.innerHTML = "No file selected.";
        }
        
        return researchText.innerHTML;
    }

    // Paper title
    modifyPaper(){
        console.log(`${this.username} is Modifying a paper`);
    }

    deletePaper(){
        console.log(`${this.username} is Deleting paper`);
    }

}

class Author extends Cair{
    constructor(username, password ,fullname){
        super(username,password,fullname);
    } 
    
    // Params tiltle
    accessReview(){
        console.log(`${this.username} is accessing peer review`);
    }

}

class Admin extends Author{
    constructor(username, password ,fullname){
        super(username,password,fullname);
    } 
}

class SuperUser extends Admin{
    constructor(username, password ,fullname){
        super(username,password,fullname);
    } 

    // Register a new CAIR member
    registerUser(username, password, fullname){
        console.log(`Admin: ${this.username} is registering a new cair member called ${username} `);
    }

    // Not required by the client
    deleteAccount(username){
        console.log(`Account ${username} is deleted`);
    }
}

