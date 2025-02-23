document.addEventListener("DOMContentLoaded", function() {
    const itemsPerPage = 6; // Maximum products per page
    let currentPage = 1;

    // Get all product cards
    const productCards = document.querySelectorAll("#product-list .card");
    const totalItems = productCards.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    // Function to display the current page
    function showPage(page) {
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        // Hide all products
        productCards.forEach((card, index) => {
            if (index >= startIndex && index < endIndex) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });

        // Update pagination controls
        document.getElementById("prevBtn").disabled = (page === 1);
        document.getElementById("nextBtn").disabled = (page === totalPages);
    }

    // Generate page numbers dynamically
    function createPageNumbers() {
        const pageNumbersContainer = document.getElementById("pageNumbers");
        pageNumbersContainer.innerHTML = ""; // Clear any existing page numbers

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement("button");
            pageButton.innerText = i;
            pageButton.classList.add("page-btn");

            // Prevent page refresh on click
            pageButton.addEventListener("click", function(event) {
                event.preventDefault(); // Prevents page reload
                currentPage = i;
                showPage(currentPage);
            });

            pageNumbersContainer.appendChild(pageButton);
        }
    }

    // Change page when clicking next/prev buttons
    document.getElementById("prevBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Prevents page reload
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    });

    document.getElementById("nextBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Prevents page reload
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    });

    // Initial setup
    createPageNumbers();
    showPage(currentPage); // Show the first page
});
