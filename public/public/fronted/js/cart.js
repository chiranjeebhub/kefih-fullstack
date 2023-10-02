const openCartButton = document.getElementById("openCart");
const closeCartButton = document.getElementById("closeCart");
const cartDrawer = document.querySelector(".cart-drawer");



// Open Cart Drawer

openCartButton.addEventListener("click", () => {
  cartDrawer.classList.add("open");
});

// Close Cart Drawer

closeCartButton.addEventListener("click", () => {
  cartDrawer.classList.remove("open");
});

// Removal of Product Card,
// Increment and Decrement of Product Quantity
// & Calculation of Subtotal

document.addEventListener("DOMContentLoaded", () => {
  function calculateSubtotal() {
    let productCards = document.querySelectorAll(".productCard");
    let subtotal = 0;
    let subItemWrapper = document.querySelector(".subItemWrapper");

    // Clear existing subItems
    subItemWrapper.innerHTML = "";

    productCards.forEach((card) => {
      let title = card.querySelector(".productCardTitle").innerText;
      let quantity = parseInt(card.querySelector(".quantity").innerText);
      let price = parseFloat(
        card.querySelector(".sellingPrice").innerText.replace("₹", "")
      );
      subtotal += price * quantity;

      // Create and add new subItem
      let subItem = document.createElement("div");
      subItem.classList.add("subItem");
      subItem.innerHTML = `
              <div>${title} (${quantity})</div>
              <div>₹${(price * quantity).toFixed(2)}</div>
          `;
      subItemWrapper.appendChild(subItem);
    });

    document.querySelector(".subTotal div:last-child").innerText =
      "₹" + subtotal.toFixed(2);
  }

  const cartDrawer = document.querySelector(".cart-drawer");

  cartDrawer.addEventListener("click", function (event) {
    // Handle product card removal
    let productCardRemoveElem = event.target.closest(".productCardRemove");

    if (productCardRemoveElem) {
      const productCard = productCardRemoveElem.closest(".productCard");
      productCard.remove();
      // Recalculate subItems and subtotal after removing a product
      calculateSubtotal();
    }

    // Handle increment and decrement buttons
    let clickedBtn = event.target;
    let countWrapper = clickedBtn.closest(".countWrapper");

    if (countWrapper) {
      let quantityElem = countWrapper.querySelector(".quantity");
      if (clickedBtn.innerText === "+") {
        quantityElem.innerText = parseInt(quantityElem.innerText) + 1;
      } else if (
        clickedBtn.innerText === "-" &&
        parseInt(quantityElem.innerText) > 1
      ) {
        quantityElem.innerText = parseInt(quantityElem.innerText) - 1;
      }

      // Recalculate subItems and subtotal after changing the product quantity
      calculateSubtotal();
    }
  });

  // Calculate the initial subItems and subtotal
  calculateSubtotal();
});
