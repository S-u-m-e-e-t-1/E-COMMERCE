
function showSuggestions(query) {
    if (query.length === 0) {
        document.getElementById('suggestions').innerHTML = "";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('suggestions').innerHTML = xhr.responseText;
        }
    };
    xhr.open("GET", "index.php?q=" + encodeURIComponent(query), true);
    xhr.send();
}

function redirectToProduct(productId) {
    window.location.href = "product_view.php?id=" + productId;
}
