console.log("x");
async function addToBasket(productNmbr) {
    console.log(productNmbr);
    let connection = await fetch(`API/add-product-to-basket.php?productNmbr=${productNmbr}`);
    let data = await connection.json();
    console.log(data);
}