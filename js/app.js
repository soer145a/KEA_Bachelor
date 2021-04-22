window.addEventListener('load', () => {
    requestApiData();
});
async function requestApiData() {
    let response = await fetch('API/api-cors-test.php');
    let data = await response.text();
    console.log(data);
}
    
