// research paper
const researchFileBtn = document.getElementById("research-file");
const uploadResearchBtn = document.getElementById("upload-research-btn");
const removeResearchBtn = document.getElementById("remove-research-btn");
const researchText = document.getElementById("research-text")

// Peer-review 
const reviewFileBtn = document.getElementById("review-file");
const uploadReviewBtn = document.getElementById("upload-review-btn");
const removeReviewBtn = document.getElementById("remove-review-btn");
const reviewText = document.getElementById("review-text");

// Abstract 
const abstractFileBtn = document.getElementById("abstract-file");
const uploadAbstractBtn = document.getElementById("upload-abstract-btn");
const removeAbstractBtn = document.getElementById("remove-abstract-btn");
const abstractText = document.getElementById("abstract-text");

// Event Listeners for uploading research paper
uploadResearchBtn.addEventListener("click", function () {
    researchFileBtn.click();
});

researchFileBtn.addEventListener('change',function(){
    if(researchFileBtn.value) {
        let fileName = researchFileBtn.value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1]; 
        if(fileName.endsWith(".pdf" )){
            researchText.innerHTML = fileName;
        }
        else{
            researchText.innerHTML = "No file selected.";
        }
    }
});

// Remove research paper selected
removeResearchBtn.addEventListener('click', function() {
    if(researchFileBtn.innerHTML !== "No file selected.") {
        researchText.innerHTML = "No file selected.";
    }
});

// Event Listeners for uploading peer review
uploadReviewBtn.addEventListener('click', function () {
    reviewFileBtn.click();
});

reviewFileBtn.addEventListener('change', function(){
    if(reviewFileBtn.value){
        let fileName = reviewFileBtn.value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
        if(fileName.endsWith(".pdf" )){
            reviewText.innerHTML = fileName;
        }
        else{
            reviewText.innerHTML = "No file selected.";
        }
    }
});

// Remove peer-review selected
removeReviewBtn.addEventListener('click', function() {
    if(reviewFileBtn.innerHTML !== "No file selected.") {
        reviewText.innerHTML = "No file selected.";
    }
});

// Event Listeners for uploading abstract text files
uploadAbstractBtn.addEventListener('click', function () {
    abstractFileBtn.click();
});

abstractFileBtn.addEventListener('change', function(){
    if(abstractFileBtn.value){
        let fileName = abstractFileBtn.value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
        if(fileName.endsWith(".txt" )){
            abstractText.innerHTML = fileName;
        }
        else{
            abstractText.innerHTML = "No file selected.";
        }
    }
});

//Remove abstract selected
removeAbstractBtn.addEventListener('click', function() {
    if(abstractFileBtn.innerHTML !== "No file selected.") {
        abstractText.innerHTML = "No file selected.";
    }
});