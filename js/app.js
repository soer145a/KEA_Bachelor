console.log("X");
window.addEventListener('load', () => {
    console.log('page is fully loaded');
    requestApiData();
});
async function requestApiData() {
    let response = await fetch('API/api-cors-test.php');
    let data = await response.text();
    console.log(data);
}
    
