document.addEventListener('DOMContentLoaded', () => {
    // 1. ТЁМНАЯ ТЕМА
    const themeToggleBtn = document.getElementById('theme-toggle');
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if(themeToggleBtn) themeToggleBtn.textContent = '☀️ Светлая тема';
    }
    if(themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            let theme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
            themeToggleBtn.textContent = theme === 'dark' ? '☀️ Светлая тема' : '🌙 Тёмная тема';
            localStorage.setItem('theme', theme);
        });
    }
});

// 2. ПОЛИТИКА КОНФИДЕНЦИАЛЬНОСТИ
function openPrivacyPolicy() {
    window.open('privacy.txt', '_blank', 'width=600,height=500,resizable=1,scrollbars=1');
}

// 3. СЛАЙДЕР
let slideIndex = 1;
window.nextSlide = function() { showSlides(slideIndex += 1); }
window.previousSlide = function() { showSlides(slideIndex -= 1); }
window.currentSlide = function(n) { showSlides(slideIndex = n); }

function showSlides(n) {
    let slides = document.getElementsByClassName("item");
    if (slides.length === 0) return; 

    if (n > slides.length) { slideIndex = 1; }
    if (n < 1) { slideIndex = slides.length; }
    
    for (let slide of slides) {
        slide.style.display = "none";
    }
    slides[slideIndex - 1].style.display = "block";
}

document.addEventListener('DOMContentLoaded', () => {
    showSlides(slideIndex);
});

// 4. КОРЗИНА ПОКУПОК
document.addEventListener('DOMContentLoaded', () => {
    function toNum(str) {
        if(!str) return 0;
        return Number(str.replace(/ /g, "").replace("руб.", "").replace(/\s/g, ''));
    }

    function toCurrency(num) {
        return new Intl.NumberFormat("ru-RU", {
            style: "currency",
            currency: "RUB",
            minimumFractionDigits: 0,
        }).format(num);
    }

    const popup = document.querySelector(".popup");
    const cartBtn = document.querySelector("#cart");
    const popupClose = document.querySelector("#popup_close");
    const body = document.body;
    const popupProductList = document.querySelector("#popup_product_list");
    const popupCostDiscount = document.querySelector("#popup_cost_discount");
    const cartNum = document.querySelector("#cart_num");
    const cardAddArr = Array.from(document.querySelectorAll(".card__add"));

    if (!popup || !cartBtn) return; 

    cartBtn.addEventListener("click", (e) => {
        e.preventDefault();
        popup.classList.add("popup--open");
        body.classList.add("lock");
        popupContainerFill(); 
    });

    popupClose.addEventListener("click", (e) => {
        e.preventDefault();
        popup.classList.remove("popup--open");
        body.classList.remove("lock");
    });

    class Product {
        constructor(card) {
            this.imageSrc = card.querySelector(".card__image img").src;
            this.name = card.querySelector(".card__title").textContent;
            this.price = card.querySelector(".card__price--common").textContent;
            this.priceDiscount = card.querySelector(".card__price--discount").textContent; 
        }
    }

    class Cart {
        constructor() {
            this.products = [];
        }
        get count() { return this.products.length; }
        addProduct(product) { this.products.push(product); }
        removeProduct(index) { this.products.splice(index, 1); }
        get cost() {
            const prices = this.products.map((product) => toNum(product.price));
            return prices.reduce((acc, num) => acc + num, 0);
        }
        get costDiscount() {
            const prices = this.products.map((product) => toNum(product.priceDiscount));
            return prices.reduce((acc, num) => acc + num, 0);
        }
        get discount() { return this.cost - this.costDiscount; }
    }

    const myCart = new Cart();

    if (localStorage.getItem("cart") == null) {
        localStorage.setItem("cart", JSON.stringify(myCart));
    }

    const savedCart = JSON.parse(localStorage.getItem("cart"));
    if (savedCart && savedCart.products) {
        myCart.products = savedCart.products;
    }
    cartNum.textContent = myCart.count;

    cardAddArr.forEach((cardAdd) => {
        cardAdd.addEventListener("click", (e) => {
            e.preventDefault();
            const card = e.target.closest(".card");
            const product = new Product(card);
            
            // Получаем доступное количество
            const availableInput = card.querySelector(".card__available");
            let maxAvailable = null;
            if (availableInput) {
                maxAvailable = parseInt(availableInput.value);
                if (isNaN(maxAvailable)) maxAvailable = null;
            }

            // Запрашиваем количество
            let quantity = 1;
            if (maxAvailable !== null && maxAvailable > 0) {
                let input = prompt(`Введите количество (доступно: ${maxAvailable} шт.)`, "1");
                if (input === null) return; // отмена
                quantity = parseInt(input);
                if (isNaN(quantity) || quantity <= 0) {
                    alert("Введите положительное число.");
                    return;
                }
                if (quantity > maxAvailable) {
                    alert(`Нельзя добавить больше ${maxAvailable} шт.`);
                    return;
                }
            } else {
                // Если нет информации о наличии, просто спрашиваем количество
                let input = prompt("Введите количество:", "1");
                if (input === null) return;
                quantity = parseInt(input);
                if (isNaN(quantity) || quantity <= 0) {
                    alert("Введите положительное число.");
                    return;
                }
            }
            
            const savedCart = JSON.parse(localStorage.getItem("cart"));
            myCart.products = savedCart.products || [];
            
            // Добавляем товар указанное количество раз
            for (let i = 0; i < quantity; i++) {
                myCart.addProduct(product);
            }
            
            localStorage.setItem("cart", JSON.stringify(myCart));
            cartNum.textContent = myCart.count;
            alert(`Товар добавлен в корзину (${quantity} шт.)!`);
        });
    });

    function popupContainerFill() {
        popupProductList.innerHTML = ''; 
        const savedCart = JSON.parse(localStorage.getItem("cart"));
        myCart.products = savedCart.products || [];
        
        const productsHTML = myCart.products.map((product, index) => {
            const productItem = document.createElement("div");
            productItem.classList.add("popup__product");

            const productWrap1 = document.createElement("div");
            productWrap1.classList.add("popup__product-wrap");
            
            const productWrap2 = document.createElement("div");
            productWrap2.classList.add("popup__product-wrap");

            const productImage = document.createElement("img");
            productImage.classList.add("popup__product-image");
            productImage.setAttribute("src", product.imageSrc);

            const productTitle = document.createElement("h2");
            productTitle.classList.add("popup__product-title");
            productTitle.innerHTML = product.name;

            const productPrice = document.createElement("div");
            productPrice.classList.add("popup__product-price");
            productPrice.innerHTML = product.priceDiscount; 

            const productDelete = document.createElement("button");
            productDelete.classList.add("popup__product-delete");
            productDelete.innerHTML = "✖ Удалить";

            productDelete.addEventListener("click", () => {
                myCart.removeProduct(index); 
                localStorage.setItem("cart", JSON.stringify(myCart));
                popupContainerFill(); 
                cartNum.textContent = myCart.count;
            });

            productWrap1.appendChild(productImage);
            productWrap1.appendChild(productTitle);
            productWrap2.appendChild(productPrice);
            productWrap2.appendChild(productDelete);
            productItem.appendChild(productWrap1);
            productItem.appendChild(productWrap2);

            return productItem;
        });

        productsHTML.forEach((productHTML) => {
            popupProductList.appendChild(productHTML);
        });

        if(popupCostDiscount) {
            popupCostDiscount.value = toCurrency(myCart.costDiscount);
        }
    }
});