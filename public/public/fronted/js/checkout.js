document.getElementById("toggle").addEventListener("change", function () {
    if (this.checked) {
        console.log("Toggle is ON");
        let subtotal = document.getElementById("subTotalSection");
        // let subtotal = $("#subTotalSection");
        let subtotal1 = document.getElementById("subTotalSection1");
        let paymentMethod = document.getElementById("paymentMethod101");
        paymentMethod.style.display = "block";

        subtotal.style.display = "block";
        subtotal1.style.display = "none";
    } else {
        console.log("Toggle is OFF");
        let subtotal = document.getElementById("subTotalSection");
        let subtotal1 = document.getElementById("subTotalSection1");
        let paymentMethod = document.getElementById("paymentMethod101");
        paymentMethod.style.display = "none";
        subtotal.style.display = "none";
        subtotal1.style.display = "block";
    }
});

// document.addEventListener("DOMContentLoaded", function () {
//   let checkbox = document.getElementById("checkbox");
//   let rightSection = document.querySelector(".rightSection");
//   let cartHeader = document.querySelector(".cartHeader");

//   // Add new div above cartHeader
//   let newDiv = document.createElement("div");
//   newDiv.innerHTML = "Your new content goes here";
//   newDiv.className = "newDivClass"; // You can style this class in your CSS
//   rightSection.insertBefore(newDiv, cartHeader);

//   checkbox.addEventListener("change", function () {
//     let subTotalSection = document.querySelector(".subTotalSection");
//     if (checkbox.checked) {
//       // Replace subTotalSection content when checkbox is checked
//       subTotalSection.innerHTML = `
//                 <div>Your new content for subTotalSection when checkbox is checked</div>
//             `;
//     } else {
//       // Replace subTotalSection content back to original or something else when checkbox is unchecked
//       subTotalSection.innerHTML = `
//                 <!-- Your original subTotalSection content or something else goes here -->
//             `;
//     }
//   });
// });

// function selectPaymentMode(element) {
//   // First, remove the 'selected' class from all divs
//   const modes = document.querySelectorAll(".paymentMode");
//   modes.forEach(function (mode) {
//     mode.classList.remove("selected");
//   });
//   console.log(element);
//   // Then, add the 'selected' class to the clicked div
//   element.classList.add("selected");
// }

function selectPaymentMode(element) {
    const modes = document.querySelectorAll(".paymentMode");

    // Check if the clicked element is already selected
    let alreadySelected = element.classList.contains("selected");

    // Remove the 'selected' class from all divs
    modes.forEach(function (mode) {
        mode.classList.remove("selected");
    });

    // If the clicked element was not previously selected, add the 'selected' class to it
    if (!alreadySelected) {
        element.classList.add("selected");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const productCards = document.querySelectorAll(
        ".productCardWrapper1 .productCard"
    );

    productCards.forEach((card) => {
        const minusBtn = card.querySelector(".minusBtn");
        const plusBtn = card.querySelector(".plusBtn");
        const quantityEl = card.querySelector(".quantity");
        const sellingPriceEl = card.querySelector(".sellingPrice");

        minusBtn.addEventListener("click", function () {
            updateQuantity(quantityEl, sellingPriceEl, -1);
            updateTotal();
        });

        plusBtn.addEventListener("click", function () {
            updateQuantity(quantityEl, sellingPriceEl, 1);
            updateTotal();
        });
    });
});

// function updateQuantity(quantityEl, priceEl, change) {
//   let currentQuantity = parseInt(quantityEl.textContent);
//   let price = parseFloat(priceEl.textContent.replace("₹", ""));

//   if (change == -1 && currentQuantity > 1) {
//     currentQuantity -= 1;
//   } else if (change == 1) {
//     currentQuantity += 1;
//   }

//   // Update quantity on the card
//   quantityEl.textContent = currentQuantity;

//   // Update item's price based on the new quantity
//   const subItemPrice = document.querySelector(
//     `.subItemWrapper1 .subItem div:contains(${
//       quantityEl.parentElement.parentElement.parentElement.querySelector(
//         ".productCardTitle"
//       ).textContent
//     }) + div`
//   );
//   if (subItemPrice) {
//     subItemPrice.textContent = `₹${price * currentQuantity}`;
//     const subItemTitle = subItemPrice.previousElementSibling;
//     const itemTitleMatch = subItemTitle.textContent.match(/(.*)\s\((\d+)\)/);
//     if (itemTitleMatch) {
//       subItemTitle.textContent = `${itemTitleMatch[1]} (${currentQuantity})`;
//     }
//   }
// }
function updateQuantity(quantityEl, priceEl, change) {
    let currentQuantity = parseInt(quantityEl.textContent);
    let price = parseFloat(priceEl.textContent.replace("₹", ""));

    if (change == -1 && currentQuantity > 1) {
        currentQuantity -= 1;
    } else if (change == 1) {
        currentQuantity += 1;
    }

    // Update quantity on the card
    quantityEl.textContent = currentQuantity;

    // Find the index of the product card
    const productCards = Array.from(document.querySelectorAll(".productCard"));
    const cardIndex = productCards.findIndex((card) =>
        card.contains(quantityEl)
    );

    // Use the index to update the corresponding subItem
    const subItem = document.querySelectorAll(".subItemWrapper1 .subItem")[
        cardIndex
    ];
    if (subItem) {
        const subItemTitle = subItem.querySelector("div:nth-child(1)");
        const subItemPrice = subItem.querySelector("div:nth-child(2)");

        subItemPrice.textContent = `₹${price * currentQuantity}`;
        const itemTitleMatch =
            subItemTitle.textContent.match(/(.*)\s\((\d+)\)/);
        if (itemTitleMatch) {
            subItemTitle.textContent = `${itemTitleMatch[1]} (${currentQuantity})`;
        }
    }
}

function updateTotal() {
    const subItems = document.querySelectorAll(
        ".subItemWrapper1 .subItem div:nth-child(2)"
    );
    let total = 0;

    subItems.forEach((item) => {
        total += parseFloat(item.textContent.replace("₹", ""));
    });

    document.querySelector(".toatlValue").textContent = `₹${total}`;
}
